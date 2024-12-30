<?php

namespace App\Domain\Agent\Application\Handlers;

use App\Domain\Agent\Application\Commands\SetAgentCommand;
use App\Domain\Agent\Infrastructure\Repositories\AgentRepositoryInterface;
use App\Domain\Agent\Domain\Entities\Agent;

class SetAgentHandler {
    protected $agentRepository;

    public function __construct(AgentRepositoryInterface $agentRepository) {
        $this->agentRepository = $agentRepository;
    }

    public function handle(SetAgentCommand $command) {
        $agent = new Agent($command->personalCode, $command->name);
        return $this->agentRepository->save($agent);
    }
}
