<?php

namespace Kim\Defender\Contracts;

interface DefenderHtmlGenerator
{
    /**
     * Create a number of random form fields.
     *
     * @param   array  $tokens
     * @return  \Illuminate\View\Expression
     */
    public function generate(array $tokens);
}
