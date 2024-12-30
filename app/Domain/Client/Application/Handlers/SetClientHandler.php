<?php

namespace App\Domain\Client\Application\Handlers;

use App\Domain\Client\Application\Commands\SetClientCommand;
use App\Domain\Client\Infrastructure\Repositories\ClientRepositoryInterface;
use App\Domain\Client\Domain\Entities\Client;

class SetClientHandler {
    protected $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository) {
        $this->clientRepository = $clientRepository;
    }

    public function handle(SetClientCommand $command) {
        $client = new Client($command->personalCode, $command->name);
        return $this->clientRepository->save($client);
    }
}
