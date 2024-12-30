<?php

namespace App\Domain\Agent\Infrastructure\Repositories;

use App\Domain\Agent\Domain\Entities\Agent;

interface AgentRepositoryInterface {
    public function save(Agent $agent);
    public function findAgentInfo(int $id);
}
