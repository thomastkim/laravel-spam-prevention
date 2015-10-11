<?php

namespace Kim\Defender\Laravel;

use Kim\Defender\Contracts\DefenderSession as DefenderSessionContract;

use Illuminate\Session\Store;
use Illuminate\Support\Str;

class DefenderSession implements DefenderSessionContract
{
    /**
     * Constant representing the key where we are storing the field names.
     *
     * @var string
     */
    const SESSION_KEY = 'defender.fields';

    /**
     * The session store instance.
     *
     * @var \Illuminate\Session\Store
     */
    private $session;

    /**
     * An array that stores the session data.
     *
     * @var array
     */
    private $fields;

    /**
     * Create a new defender session instance.
     *
     * @param  \Illuminate\Session\Store  $session
     * @return void
     */
    public function __construct(Store $session)
    {
        $this->session = $session;

        $this->fields = $this->get(self::SESSION_KEY);
    }

    /**
     * Get the randomized token stored in the session.
     *
     * @param  string  $key
     * @return string|array
     */
    public function get($key)
    {
        return $this->session->get($key, []);
    }

    /**
     * Get all randomized defender tokens.
     *
     * @return array
     */
    public function all()
    {
        return $this->fields;
    }

    /**
     * Store the randomized token if it does not already exist.
     *
     * @param  string  $key
     * @return string|array
     */
    public function put($key)
    {
        if ($this->isKeySet($key))
        {
            return $this->fields[$key];
        }

        return $this->set($key);
    }

    /**
     * Create and flash the bait.
     *
     * @return string
     */
    public function putBait()
    {
        $baitTag = $this->getBaitTag();
        $baitValue = $this->generateRandomString();

        $temp = $this->get(self::SESSION_KEY . ".{$baitTag}");
        $temp[] = $baitValue;

        $this->session->flash(self::SESSION_KEY . ".{$baitTag}", $temp);

        return $baitValue;
    }

    /**
     * Check if there is any bait inside the default bait namespace.
     *
     * @return boolean
     */
    public function hasBait()
    {
        return $this->session->has(self::SESSION_KEY . '.bait');
    }

    /**
     * Get all bait values.
     *
     * @return array
     */
    public function getBait()
    {
        return array_merge($this->get(self::SESSION_KEY . '.bait'), $this->get(self::SESSION_KEY . '.bait-2'));
    }

    /**
     * Get current session's bait's tag.
     *
     * @return string
     */
    private function getBaitTag()
    {
        return ($this->hasBait()) ? 'bait-2' : 'bait';
    }

    /**
     * Check if the key is already in use.
     *
     * @param  string  $key
     * @return boolean
     */
    private function isKeySet($key)
    {
        return isset($this->fields[$key]);
    }

    /**
     * Get a randomized token and store it.
     *
     * @param  string  $key
     * @return string
     */
    private function set($key)
    {
        $this->fields[$key] = $this->generateRandomString();
        $this->session->put(self::SESSION_KEY, $this->fields);

        return $this->fields[$key];
    }

    /**
     * Generate a 36-character long randomized string.
     *
     * @return string
     */
    private function generateRandomString()
    {
        return Str::random(36);
    }

}
