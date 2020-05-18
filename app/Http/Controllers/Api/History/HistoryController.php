<?php

namespace App\Http\Controllers\Api\History;

use App\Http\Controllers\Controller;
use App\Http\Services\History\HistoryService;

class HistoryController extends Controller
{
    protected $historyService;

    /**
     * HistoryController constructor.
     */
    public function __construct() {
        $this->historyService = new HistoryService();
    }

    public function getExpenditureInfo () {
        $response = $this->historyService->userExpenditureInfo();

        return response()->json($response);
    }
}
