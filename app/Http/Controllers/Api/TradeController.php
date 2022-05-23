<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trade\CreateTradeRequest;
use App\Services\TradeService;
use Exception;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    private $tradeService;

    public function __construct(TradeService $tradeService)
    {
        $this->tradeService = $tradeService;
    }

    public function createTrade(CreateTradeRequest $request)
    {
        try {
            $result = $this->tradeService->createTrade($request);

            return $this->success("Successfully trade items", 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
