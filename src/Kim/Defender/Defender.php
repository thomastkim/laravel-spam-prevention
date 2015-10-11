<?php

namespace Kim\Defender;

use Kim\Defender\Contracts\DefenderSession;
use Kim\Defender\Contracts\DefenderHtmlGenerator;

class Defender
{
    /**
     * The defender session handler.
     *
     * @var \Kim\Defender\Contracts\DefenderSession
     */
    private $session;

    /**
     * The html generator.
     *
     * @var \Kim\Defender\Contracts\DefenderHtmlGenerator
     */
    private $html;

    /**
     * Create a new defender instance.
     *
     * @param  \Kim\Defender\Contracts\DefenderSession  $session
     * @param  \Kim\Defender\Contracts\DefenderHtmlGenerator  $html
     * @return void
     */
    public function __construct(DefenderSession $session, DefenderHtmlGenerator $html)
    {
        $this->session = $session;
        $this->html = $html;
    }

    /**
     * Get the email's randomized token.
     *
     * @return  string
     */
    public function email()
    {
        return $this->get('email');
    }

    /**
     * Get the username's randomized token.
     *
     * @return  string
     */
    public function username()
    {
        return $this->get('username');
    }

    /**
     * Get the password's randomized token.
     *
     * @return  string
     */
    public function password()
    {
        return $this->get('password');
    }

    /**
     * Get the passwordConfirmation's randomized token.
     *
     * @return  string
     */
    public function passwordConfirmation()
    {
        return $this->get('password_confirmation');
    }

    /**
     * Get the token associated with a particular input key.
     *
     * @param   string  $key
     * @return  string
     */
    public function get($key)
    {
        if ($key == 'bait' || $key == 'bait-2')
        {
            return $this->baitToken();
        }
        return $this->session->put($key);
    }

    /**
     * Get all the tokens that are currently stored in the session.
     *
     * @return  string
     */
    public function all()
    {
        return $this->session->all();
    }

    /**
     * Get a randomized token as bait.
     *
     * @return  string
     */
    public function baitToken()
    {
        return $this->session->putBait();
    }

    /**
     * Output a specific number of bait input fields.
     *
     * @param   int  $numberOfInput
     * @return  \Illuminate\View\Expression
     */
    public function baitField($numberOfInput = 1)
    {
        $tokens = [];

        for ($i = 0; $i < $numberOfInput; $i++)
        {
            $tokens[] = $this->baitToken();
        }

        return $this->html->generate($tokens);
    }

    /**
     * Output a randomized number of bait input fields.
     *
     * @param   int  $max
     * @return  \Illuminate\View\Expression
     */
    public function baitFields($max = 5)
    {
        $numberOfInput = rand(1, $max);

        return $this->baitField($numberOfInput);
    }

    /**
     * Get all bait tokens.
     *
     * @return array
     */
    public function bait()
    {
        return $this->session->getBait();
    }

}
