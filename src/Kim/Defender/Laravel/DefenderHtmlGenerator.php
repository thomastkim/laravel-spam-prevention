<?php

namespace Kim\Defender\Laravel;

use Illuminate\View\Expression;
use InvalidArgumentException;
use Kim\Defender\Contracts\DefenderHtmlGenerator as DefenderHtmlGeneratorContract;

class DefenderHtmlGenerator implements DefenderHtmlGeneratorContract
{
    /**
     * An array of input types.
     *
     * @var array
     */
    private $types = ['hidden', 'text', 'email', 'password', 'radio', 'checkbox', 'button'];

    /**
     * An array of styling to hide the input.
     *
     * @var array
     */
    private $styles = [
        'display:none',
        'position:absolute;top:-9999px;left:-9999px',
        'position:fixed;visibility:hidden;z-index:-1'
    ];

    /**
     * Create a number of random form fields.
     *
     * @param   array  $tokens
     * @return  \Illuminate\View\Expression
     */
    public function generate(array $tokens)
    {
        if ($this->doesNotHaveTokens($tokens)) throw new InvalidArgumentException('You most provide at least one token.');

        $html = '';

        foreach ($tokens as $token)
        {
            $html .= '<input type="' . $this->getRandomType() . '" name="' . $token . '" style="' . $this->getRandomStyling() . '">';
        }

        return new HtmlString($html);
    }

    /**
     * Get a random input type.
     *
     * @return  string
     */
    private function getRandomType()
    {
        return $this->types[array_rand($this->types)];
    }

    /**
     * Get a random input styling.
     *
     * @return  string
     */
    private function getRandomStyling()
    {
        return $this->styles[array_rand($this->styles)];
    }

    /**
     * Check if the array holds at least one token.
     *
     * @param   array  $tokens
     * @return  boolean
     */
    private function doesNotHaveTokens(array $tokens)
    {
        return count($tokens) === 0;
    }
}
