<?php

namespace App\Domain\Booking\Application\Handlers;

use App\Domain\Booking\Application\Commands\SetBookingCommand;
use App\Domain\Booking\Infrastructure\Repositories\BookingRepositoryInterface;
use App\Domain\Booking\Domain\Entities\Booking;

class SetBookingHandler {
    protected $bookingRepository;

    public function __construct(BookingRepositoryInterface $bookingRepository) {
        $this->bookingRepository = $bookingRepository;
    }

    public function handle(SetBookingCommand $command) {
        $booking = new Booking($command->clientId, $command->startDateTime, $command->endDateTime);

        return $this->bookingRepository->save($booking);
    }
}
