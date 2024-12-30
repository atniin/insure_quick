<?php

namespace App\Domain\Booking\Application\Commands;

class SetBookingCommand {
    public $clientId;
    public $startDateTime;
    public $endDateTime;

    public function __construct($clientId, $startDateTime, $endDateTime) {
        $this->clientId = $clientId;
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
    }
}
