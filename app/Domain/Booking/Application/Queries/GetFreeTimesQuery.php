<?php

namespace App\Domain\Booking\Application\Queries;

class GetFreeTimesQuery {
    public $date;

    public function __construct($date) {
        $this->date = $date;
    }
}
