<?php

namespace App\Domain\Booking\Infrastructure\Repositories;

use App\Domain\Booking\Domain\Entities\Booking;

interface BookingRepositoryInterface {
    public function save(Booking $booking);
    public function findAvailableTimeslots(string $date);
}
