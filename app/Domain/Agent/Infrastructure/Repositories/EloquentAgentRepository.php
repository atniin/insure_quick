<?php

namespace App\Domain\Agent\Infrastructure\Repositories;
use App\Domain\Agent\Domain\Entities\Agent;
use App\Domain\Agent\Infrastructure\Repositories\AgentRepositoryInterface;
use App\Models\Agent as EloquentAgent;

class EloquentAgentRepository implements AgentRepositoryInterface {

    public function save(Agent $agent) {
        $existingAgent = EloquentAgent::where('personal_code', $agent->personalCode)->first();
        if ($existingAgent){
            return ["already exists.", $existingAgent, 200];
        }
        return [
            "created successfully.",
            EloquentAgent::create([
                'personal_code' => $agent->personalCode,
                'name' => $agent->name
            ]),
            201
        ];
    }

    public function findAgentInfo(int $id) {
        return EloquentAgent::where('id', $id)->first();
    }
}
