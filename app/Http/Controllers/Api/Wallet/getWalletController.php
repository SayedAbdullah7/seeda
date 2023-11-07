<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\services\walletService;

class getWalletController extends Controller
{
    private walletService $walletService;

    public function __construct()
    {
        $this->walletService  = new walletService();
    }

    public function index()
    {
        return $this->walletService->getWalletData(auth()->id());
    }
}
