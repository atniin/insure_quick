<?php

namespace App\Domain\Booking\Infrastructure\Repositories;
use App\Domain\Booking\Domain\Entities\Booking;
use App\Domain\Booking\Infrastructure\Repositories\BookingRepositoryInterface;
use App\Models\Booking as EloquentBooking;
use App\Models\Agent;
use Illuminate\Support\Facades\DB;

use DateTime;
use DateInterval;

class EloquentBookingRepository implements BookingRepositoryInterface {
    private array $availableTimeslots;

    public function __construct() {
        $this->availableTimeslots = $this->generateTimeslots('09:00:00', '17:00:00', 'PT15M'); 

    }
    public function save(Booking $booking) {
        // check if booking slot is still available and avoid race condition
        $bookingStartTimeString = $booking->startDateTime->format('Y-m-d H:i:s');
        $bookingEndTimeString = $booking->endDateTime->format('Y-m-d H:i:s');
        DB::beginTransaction();
        try {
            $agents = Agent::whereNull('deleted_at')
            ->where('created_at', '<', now())
            ->get();

            foreach ($agents as $agent) {
                $agentHasBooking = EloquentBooking::where('agent_id', $agent->id)
                ->whereNull('deleted_at')
                ->where(function ($query) use ($booking) {
                    $query->whereBetween('start_datetime', [$booking->startDateTime, $booking->endDateTime])
                          ->orWhereBetween('end_datetime', [$booking->startDateTime, $booking->endDateTime]);
                })
                ->exists(); 

                if ($agentHasBooking) {
                    continue;
                }

                $agentBookings = EloquentBooking::where('agent_id', $agent->id)
                ->whereNull('deleted_at')
                ->lockForUpdate()
                ->get();

                $newBooking = EloquentBooking::create([
                    'client_id' => $booking->clientId,
                    'agent_id' => $agent->id,
                    'start_datetime' => $bookingStartTimeString,
                    'end_datetime' => $bookingEndTimeString,
                ]);

                $createdBooking = EloquentBooking::find($newBooking->id);
                DB::commit();
                $newBookingFormatted = [
                    'id' => $newBooking->id,
                    'client_id' => (int)$newBooking->client_id,
                    'agent_id' => $newBooking->agent_id,
                    'start_datetime' => $bookingStartTimeString,
                    'end_datetime' => $bookingEndTimeString, 
                ];
                return [
                    $newBookingFormatted, 
                    "Booking created successfully.", 
                    201
                ];
            }
            DB::rollBack();
            return [null, 'No available agents for the selected time slot.', 400];
        } catch (\Exception $e) {
            DB::rollBack();
            return [null, 'An error occurred while processing the booking.' . $e, 500];
        }

    }

    private function generateTimeslots($start, $end, $interval){
        $start = new DateTime($start);
        $end = new DateTime($end);
        $interval = new DateInterval($interval);

        $timeslots = [];
        while ($start < $end) {
            $next = clone $start;
            $next->add($interval);
            $timeslots[] = [
                $start->format('H:i:s'), 
                $next->format('H:i:s'),  
                0, // placeholder - 0 = not available, 1 = available
            ];
            $start = $next;
        }
        return $timeslots;
    }

    /**
     * Finds available timeslots for a given date.
     *
     * This method checks if the provided date is valid, ensures it falls on a workday (Monday to Friday),
     * and checks whether the date is neither in the past nor more than 90 days in the future.
     * If the date is valid and meets the criteria, it returns available timeslots.
     * If the date is invalid, returns 404.
     * If the date is too far in the future or in the past, returns 200 and returns timeslots as placeholder.
     * 
     * @param string $date The date for which to find available timeslots, in a valid format (e.g., 'Y-m-d').
     * 
     * @return array Returns an array containing:
     *              - An array of available timeslots if the date is valid.
     *              - A message if status code isn't 200
     *              - A status code (200, or 404) indicating the result of the operation.
     */
    public function findAvailableTimeslots(string $date) {
        // Check for an invalid input
        try{
            $dateTime = new DateTime($date);
        } catch (Exception $e) {
            return [false, "Wrong date format", 404];
        }
        
        // Other checks for inputs where it’s unnecessary to check if any slots are available 
        $dayNum = (int)$dateTime->format('N');
        $isWorkDay = $dayNum >= 1 && $dayNum <= 5;
        if (!($isWorkDay)){
            return [$this->availableTimeslots, null, 200];
        }

        $curDate = new DateTime();
        if ($dateTime->format('Y-m-d') < $curDate->format('Y-m-d')) {
            return [$this->availableTimeslots, null, 200]; 
        }

        $curDatePlus90Days = (clone $curDate)->modify('+90 days');
        if ($dateTime > $curDatePlus90Days) {
            return [false, null, 200];
        }

        return [$this->findAvailableSpots($dateTime, $curDate), null, 200];
    }

    private function findAvailableSpots($dateTime, $curDate) {
        $start = "09:00:00";
        if ($dateTime->format('Y-m-d') == $curDate->format('Y-m-d')){
            $start = $curDate->format('H:i:s');
        }
        $end = "17:00:00";

        if ($start>$end) {
            return $this->availableTimeslots;
        }

        $agents = Agent::whereDate('created_at', '<=', $dateTime->format('Y-m-d')) 
        ->whereNull('deleted_at') 
        ->get();        

        foreach ($this->availableTimeslots as &$timeslotRow) {
            list($startInRow, $endInRow) = $timeslotRow;
            if ($start > $startInRow) {
                continue;
            }
            $startDateTime = clone $dateTime;
            $endDateTime = clone $dateTime;
            $startDateTime->setTime(...explode(':', $startInRow));
            $endDateTime->setTime(...explode(':', $endInRow));

            foreach ($agents as $agent) {
                if ($agent->created_at > $curDate) {
                    continue;
                }
                $agentHasBooking = EloquentBooking::where('agent_id', $agent->id)
                ->where('start_datetime', '<=', $startDateTime)
                ->where('end_datetime', '>=', $endDateTime)
                ->whereNull('deleted_at')
                ->exists();
                if($agentHasBooking){
                    continue;
                }
                $timeslotRow[2] = 1;
                break;
            }
        }
        return $this->availableTimeslots;
        
    } 
}
