<?php

namespace App\Http\Controllers;

use App\Domain\Client\Application\Queries\GetClientQuery;
use App\Domain\Client\Application\Handlers\GetClientHandler;
use App\Domain\Client\Application\Commands\SetClientCommand;
use App\Domain\Client\Application\Handlers\SetClientHandler;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
class ClientController extends Controller {
    protected $getClientHandler;
    protected $setClientHandler;

    public function __construct(
        GetClientHandler $getClientHandler,
        SetClientHandler $setClientHandler
    ) {
        $this->getClientHandler = $getClientHandler;
        $this->setClientHandler = $setClientHandler;
    }

    public function getClientById($id) {
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
        $query = new GetClientQuery($id);
        $clientInfo = $this->getClientHandler->handle($query);
        if (!$clientInfo){
            return response()->json([
                'message' => 'Client not found',
            ], 404);

        }
        return response()->json(['clientInfo' => $clientInfo]);
    }

    public function createClient(Request $request) {
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

        $command = new SetClientCommand($request->personal_code, $request->name);
        list($msg, $client, $code) = $this->setClientHandler->handle($command);
        
        return response()->json([
            'message' => "Client $msg",
            'client' => $client
        ], $code);
    }

}
