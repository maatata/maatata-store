<?php

namespace App\Yantrana\__Laraware\Core;

/*
 * Core Repository - 0.4.1 - 23 JUN 2016
 * 
 * Base Repository for Laravel applications
 *
 *
 * Dependencies:
 * 
 * Laravel     5.0 +     - http://laravel.com
 * 
 *
 *-------------------------------------------------------- */

use DB;
use Closure;
use Exception;
use Cache;

abstract class CoreRepository
{
    /**
     * enable or disable caching viaCache.
     *
     * @var string
     */
    protected $enableCache = true;

    /**
     * DB transaction process.
     *
     * @param Closure $callback
     */
    public function processTransaction(Closure $callback)
    {
        $reactionCode = 14;
        $returnProcessReaction = null;

        DB::beginTransaction();

        // We'll simply execute the given callback within a try / catch block
        // and if we catch any exception we can rollback the transaction
        // so that none of the changes are persisted to the database.
        try {
            $reactionCode = $callback($this);

            if (is_array($reactionCode) === true) {
                $returnProcessReaction = $reactionCode;
                $reactionCode = $reactionCode[0];
            }

            if ($reactionCode == 1) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        }

        // If we catch an exception, we will roll back so nothing gets messed
        // up in the database. Then we'll re-throw the exception so it can
        // be handled how the developer sees fit for their applications.

        catch (Exception $e) {
            DB::rollBack();

            $reactionCode = 19;

            throw $e;
        }

        return (__isEmpty($returnProcessReaction) === true)
            ? $reactionCode : $returnProcessReaction;
    }

    /**
     * To return response from Process Transaction.
     *
     * @param array  $reactionCode - Reaction from Repo
     * @param array  $data         - Array of data if needed
     * @param string $message      - Message if any
     * 
     * @return array
     *---------------------------------------------------------------- */
    public function transactionResponse($reactionCode, $data = null, $message = null)
    {
        return [
            $reactionCode,
            $data,
            $message,
        ];
    }

    /**
     * Controllable Cache function for DB Queries.
     *
     * @param string      $cacheId           - Cache ID/Key
     * @param int/Closure $minutesOrCallback - Number of minutes to remember / Callback containing query to cache
     * @param Closure     $callback          - Callback containing query to cache
     * 
     * @return array
     *---------------------------------------------------------------- */
    public function viaCache($cacheId, $minutesOrCallback, Closure $callback = null)
    {
        // check if query cache disabled
        if ((env('ENABLE_DB_CACHE', true) == false) or ($this->enableCache === false)) {
            // minutes not sent it must be callable function
            if ($minutesOrCallback instanceof Closure) {
                return $minutesOrCallback();
            }

            return $callback();
        }

        // if minutes sent then remember accordingly 
        if ($minutesOrCallback and is_numeric($minutesOrCallback) == true) {
            return Cache::remember($cacheId, $minutesOrCallback, $callback);
        }
        // minutes not sent it must be callable function
        if ($minutesOrCallback instanceof Closure) {
            return Cache::rememberForever($cacheId, $minutesOrCallback);
        }

        return Cache::rememberForever($cacheId, $callback);
    }

    /**
     * Remove Cache item.
     *
     * @param string $cacheId - Cache ID/Key
     * 
     * @return array
     *---------------------------------------------------------------- */
    public function clearCache($cacheId)
    {
        return Cache::forget($cacheId);
    }
}
