<?php

namespace App\Domain\Agent\Application\Handlers;

use App\Domain\Agent\Application\Queries\GetAgentQuery;
use App\Domain\Agent\Infrastructure\Repositories\AgentRepositoryInterface;

class GetAgentHandler {
    protected $agentRepository;

    public function __construct(AgentRepositoryInterface $agentRepository) {
        $this->agentRepository = $agentRepository;
    }

    public function handle(GetAgentQuery $query) {
        return $this->agentRepository->findAgentInfo($query->id);
    }
}
