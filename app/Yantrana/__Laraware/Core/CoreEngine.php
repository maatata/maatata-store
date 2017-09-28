<?php

namespace App\Yantrana\__Laraware\Core;

/*
 * Core Engine - 0.1.0 - 18 JAN 2016
 * 
 * core engine for Angulara (Laraware) applications
 *
 * @since 0.1.174 - 24 JUN 2016
 *--------------------------------------------------------------------------- */

use Exception;

abstract class CoreEngine
{
    /**
     * Send reaction from Engine mostly to Controllers.
     *
     * @param array  $reactionCode - Reaction from Repo
     * @param array  $data         - Array of data if needed
     * @param string $message      - Message if any
     * 
     * @return array
     *-------------------------------------------------------------------------- */
    public function engineReaction($reactionCode, $data = null, $message = null)
    {
        //
        if (is_array($reactionCode) === true) {
            $message = $reactionCode[2];
            $data = $reactionCode[1];
            $reactionCode = $reactionCode[0];
        }

        if (__isValidReactionCode($reactionCode) === true) {
            return [
                'reaction_code' => (integer) $reactionCode,
                'data' => $data,
                'message' => $message,
            ];
        }

        throw new Exception('__engineReaction:: Invalid Reaction Code!!');
    }
}
