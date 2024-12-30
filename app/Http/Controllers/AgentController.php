<?php

namespace App\Http\Controllers;

use App\Domain\Agent\Application\Queries\GetAgentQuery;
use App\Domain\Agent\Application\Handlers\GetAgentHandler;
use App\Domain\Agent\Application\Commands\SetAgentCommand;
use App\Domain\Agent\Application\Handlers\SetAgentHandler;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AgentController extends Controller{
    protected $getAgentHandler;
    protected $setAgentHandler;

    public function __construct(
        GetAgentHandler $getAgentHandler,
        SetAgentHandler $setAgentHandler
    ) {
        $this->getAgentHandler = $getAgentHandler;
        $this->setAgentHandler = $setAgentHandler;
    }

    public function getAgentById($id) {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|numeric']
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $query = new GetAgentQuery($id);
        $agentInfo = $this->getAgentHandler->handle($query);
        if (!$agentInfo){
            return response()->json([
                'message' => 'Agent not found',
            ], 404);

        }
        return response()->json(['agentInfo' => $agentInfo]);
    }

    public function createAgent(Request $request) {
        $validator = Validator::make(
            [
                'personal_code' => $request->personal_code,
                'name' => $request->name,
            ],
            [
                'personal_code' => 'required|string',
                'name' => 'required|string',
            
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $command = new SetAgentCommand($request->personal_code, $request->name);
        list($msg, $agent, $code) = $this->setAgentHandler->handle($command);
        
        return response()->json([
            'message' => "Agent $msg",
            'agent' => $agent
        ], $code);
    }

}
