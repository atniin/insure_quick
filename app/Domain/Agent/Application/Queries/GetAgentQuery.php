<?php

namespace App\Domain\Agent\Application\Queries;

class GetAgentQuery {
    public $id;

    public function __construct($id) {
        $this->id = $id;
    }
}
