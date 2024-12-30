<?php

namespace App\Domain\Client\Application\Queries;

class GetClientQuery {
    public $id;

    public function __construct($id) {
        $this->id = $id;
    }
}
