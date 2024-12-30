<?php

namespace App\Domain\Client\Application\Commands;

class SetClientCommand {
    public $personalCode;
    public $name;

    public function __construct($personalCode, $name) {
        $this->personalCode = $personalCode;
        $this->name = $name;
    }
}
