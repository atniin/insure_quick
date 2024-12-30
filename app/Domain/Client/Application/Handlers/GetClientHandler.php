<?php

namespace App\Domain\Client\Application\Handlers;

use App\Domain\Client\Application\Queries\GetClientQuery;
use App\Domain\Client\Infrastructure\Repositories\ClientRepositoryInterface;

class GetClientHandler {
    protected $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository) {
        $this->clientRepository = $clientRepository;
    }

    public function handle(GetClientQuery $query) {
        return $this->clientRepository->findClientInfo($query->id);
    }
}
