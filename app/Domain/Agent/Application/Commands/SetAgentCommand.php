<?php

namespace App\Domain\Agent\Application\Commands;

class SetAgentCommand {
    public $personalCode;
    public $name;

    public function __construct($personalCode, $name) {
        $this->personalCode = $personalCode;
        $this->name = $name;
    }
}
