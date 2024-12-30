<?php

namespace App\Domain\Agent\Domain\Entities;

class Agent {
    public $personalCode;
    public $name;

    public function __construct($personalCode, $name) {
        $this->personalCode = $personalCode;
        $this->name = $name;
    }
}
