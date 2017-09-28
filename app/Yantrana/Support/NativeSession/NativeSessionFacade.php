<?php

namespace App\Yantrana\Support\NativeSession;

/*
 * Facade for NativeSession
 *-------------------------------------------------------- */

use Illuminate\Support\Facades\Facade;

/**
 * NativeSession.
 *-------------------------------------------------------------------------- */
class NativeSessionFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'NativeSession';
    }
}
