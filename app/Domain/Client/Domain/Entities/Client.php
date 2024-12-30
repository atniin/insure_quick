<?php

namespace App\Domain\Client\Domain\Entities;

class Client {
    public $personalCode;
    public $name;

    public function __construct($personalCode, $name) {
        $this->personalCode = $personalCode;
        $this->name = $name;
    }
}
