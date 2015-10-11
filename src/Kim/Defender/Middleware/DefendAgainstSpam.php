<?php

namespace Kim\Defender\Middleware;

use Closure;
use Defender;
use Kim\Defender\Exceptions\InvalidFormException;

class DefendAgainstSpam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        foreach (Defender::bait() as $bait)
        {
            if ($this->doesBaitHaveValue($request->input($bait))) {
                throw new InvalidFormException;
            }
        }
        return $next($request);
    }

    /**
     * Reject any bait input that has a value.
     *
     * @param  string  $bait
     * @return bool
     */
    public function doesBaitHaveValue($bait)
    {
        if (is_null($bait))
        {
            return false;
        }
        elseif (is_string($bait) && $bait === '')
        {
            return false;
        }
        elseif ((is_array($bait) || $bait instanceof Countable) && count($bait) < 1)
        {
            return false;
        }
        elseif ($bait instanceof File)
        {
            return (string) $bait->getPath() != '';
        }
        return true;
    }
}
