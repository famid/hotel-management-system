<?php

namespace App\Http\Controllers\Api\Revenue;

use App\Http\Controllers\Controller;
use App\Http\Services\Revenue\RevenueService;
use Illuminate\Http\JsonResponse;

class RevenueController extends Controller
{
    protected $revenueService;

    /**
     * RevenueController constructor.
     */
    public function __construct() {
        $this->revenueService = new RevenueService();
    }

    /**
     * @return JsonResponse
     */
    public function getUserRevenueInfo () {
        $response = $this->revenueService->userRevenueInfo();

        return response()->json($response);
    }
}
