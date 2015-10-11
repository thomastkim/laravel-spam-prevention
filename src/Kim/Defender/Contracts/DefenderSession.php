<?php

namespace Kim\Defender\Contracts;

interface DefenderSession
{

    /**
     * Get the randomized token stored in the session.
     *
     * @param  string  $key
     * @return string|array
     */
    public function get($key);

    /**
     * Store the randomized token if it does not already exist.
     *
     * @param  string  $key
     * @return string|array
     */
    public function put($key);

    /**
     * Create and flash the bait.
     *
     * @return string
     */
    public function putBait();
}
