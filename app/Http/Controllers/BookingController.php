<?php

namespace App\Http\Controllers;

use App\Domain\Booking\Application\Queries\GetFreeTimesQuery;
use App\Domain\Booking\Application\Handlers\GetFreeTimesHandler;
use App\Domain\Booking\Application\Commands\SetBookingCommand;
use App\Domain\Booking\Application\Handlers\SetBookingHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;
use DateInterval;

class BookingController extends Controller
{
    protected $getFreeTimesHandler;
    protected $setBookingHandler;

    public function __construct(
        GetFreeTimesHandler $getFreeTimesHandler,
        SetBookingHandler $setBookingHandler,
    ) {
        $this->getFreeTimesHandler = $getFreeTimesHandler;
        $this->setBookingHandler = $setBookingHandler;
    }

    public function getFreeTimes($date)
    {
        $validator = Validator::make(
            ['date' => $date],
            ['date' => 'required|date']
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $query = new GetFreeTimesQuery($date);
        list($timeList, $message, $statusCode) = $this->getFreeTimesHandler->handle($query);
        return response()->json([
            'availableTimes' => $timeList,
            'message' => $message,
        ], $statusCode);
    }

    public function createBooking(Request $request) {
        // validate input
        $validator = Validator::make(
            [
                'client_id' => $request->client_id,
                'start_datetime' => $request->start_datetime,
                'end_datetime' => $request->end_datetime,
            ],
            [
                'client_id' => 'required|integer',
                'start_datetime' => 'required|date',
                'end_datetime' => 'required|date',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // other validations
        $startTime = new DateTime($request->start_datetime);
        $endTime = new DateTime($request->end_datetime);
        $startMinutes = (int)$startTime->format('i');
        $endMinutes = (int)$endTime->format('i');
        $startSeconds = (int)$startTime->format('s');
        $endSeconds = (int)$endTime->format('s');
        $validMinutes = [0, 15, 30, 45];

        if (!in_array($startMinutes, $validMinutes) || !in_array($endMinutes, $validMinutes)) {
            return response()->json([
                'message' => 'Minutes must be in [00, 15, 30, 45] for both times.'
            ], 400);
        }
        if ($startSeconds != 0 || $endSeconds != 0){
            return response()->json([
                'message' => 'Seconds must be 00 for both times.'
            ], 400);            
        }
        if ($startTime >= $endTime) {
            return response()->json([
                'message' => 'Start time must be earlier than end time.'
            ], 400);
        }

        $minInterval = new DateInterval('PT15M'); 
        $maxInterval = new DateInterval('PT2H'); 
        $minValidEndTime = (clone $startTime)->add($minInterval);
        $maxValidEndTime = (clone $startTime)->add($maxInterval);
        if ($endTime < $minValidEndTime || $endTime > $maxValidEndTime) {
            return response()->json([
                'message' => 'End time must be at least 15 minutes and at most 2 hours after the start time.'
            ], 400);
        }

        $earliestStart = (clone $startTime)->setTime(9, 0);
        $latestEnd = (clone $endTime)->setTime(17, 0);
        if ($startTime < $earliestStart || $endTime > $latestEnd) {
            return response()->json([
                'message' => 'Start time must be at least 9:00 AM, and end time must be at most 5:00 PM.'
            ], 400);
        }

        $currentDateTime = new DateTime();
        if ($startTime < $currentDateTime) {
            return response()->json([
                'message' => 'Start time cannot be in the past.'
            ], 400);
        }

        $ninetyDaysLater = (clone $currentDateTime)->add(new DateInterval('P90D'));
        if ($startTime > $ninetyDaysLater) {
            return response()->json([
                'message' => 'Start time cannot be more than 90 days in the future.'
            ], 400);
        }        

        // main
        $command = new SetBookingCommand($request->client_id, $startTime, $endTime);
        list($booking, $message, $statusCode) = $this->setBookingHandler->handle($command);
        return response()->json([
            'booking' => $booking,
            'message' => $message,
        ], $statusCode);
    }


}
