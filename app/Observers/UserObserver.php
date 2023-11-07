<?php

namespace App\Observers;

use App\Models\User;
use App\services\PointService;
use App\services\walletService;

class UserObserver
{
    private walletService $walletService;

    public function __construct()
    {
        $this->walletService  = new walletService();
    }
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\user  $user
     * @return void
     */
    public function created(user $user)
    {
        $this->walletService->createWallet($user->id);
        PointService::createPoint($user->id);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\user  $user
     * @return void
     */
    public function updated(user $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\user  $user
     * @return void
     */
    public function deleted(user $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\user  $user
     * @return void
     */
    public function restored(user $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\user  $user
     * @return void
     */
    public function forceDeleted(user $user)
    {
        //
    }
}
