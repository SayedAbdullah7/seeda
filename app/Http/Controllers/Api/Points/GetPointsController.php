<?php

namespace App\Http\Controllers\Api\Points;

use App\Http\Controllers\Controller;
use App\Http\Requests\Points\GetPointsRequest;
use App\services\PointService;
use Illuminate\Http\Request;

class GetPointsController extends Controller
{

    private PointService $PointService;

    public function __construct()
    {
        $this->PointService  = new PointService();
    }

    public function index()
    {
        return $this->PointService->getPointData(auth()->id());
    }
}
