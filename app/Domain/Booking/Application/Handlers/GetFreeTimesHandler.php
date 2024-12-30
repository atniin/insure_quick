<?php

namespace App\Domain\Booking\Application\Handlers;

use App\Domain\Booking\Application\Queries\GetFreeTimesQuery;
use App\Domain\Booking\Infrastructure\Repositories\BookingRepositoryInterface;

class GetFreeTimesHandler {
    protected $bookingRepository;

    public function __construct(BookingRepositoryInterface $bookingRepository) {
        $this->bookingRepository = $bookingRepository;
    }

    public function handle(GetFreeTimesQuery $query) {
        return $this->bookingRepository->findAvailableTimeslots($query->date);
    }
}
