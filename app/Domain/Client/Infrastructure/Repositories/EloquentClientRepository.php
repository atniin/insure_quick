<?php

namespace App\Domain\Client\Infrastructure\Repositories;
use App\Domain\Client\Domain\Entities\Client;
use App\Domain\Client\Infrastructure\Repositories\ClientRepositoryInterface;
use App\Models\Client as EloquentClient;

class EloquentClientRepository implements ClientRepositoryInterface {

    public function save(Client $client) {
        $existingClient = EloquentClient::where('personal_code', $client->personalCode)->first();
        if ($existingClient){
            return ["already exists.", $existingClient, 200];
        }
        return [
            "created successfully.",
            EloquentClient::create([
                'personal_code' => $client->personalCode,
                'name' => $client->name
            ]),
            201
        ];
    }

    public function findClientInfo(int $id) {
        return EloquentClient::where('id', $id)->first();
    }
}
