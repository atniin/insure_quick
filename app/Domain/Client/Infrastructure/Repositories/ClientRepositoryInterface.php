<?php

namespace App\Domain\Client\Infrastructure\Repositories;
use App\Domain\Client\Domain\Entities\Client;

interface ClientRepositoryInterface {
    public function save(Client $client);
    public function findClientInfo(int $id);
}
