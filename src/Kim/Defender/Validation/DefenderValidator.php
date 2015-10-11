<?php

namespace Kim\Defender\Validation;

use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\File\File;

use Defender;

class DefenderValidator extends Validator
{
    /**
     * Create a new Validator instance.
     *
     * @param  \Symfony\Component\Translation\TranslatorInterface  $translator
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @return void
     */
    public function __construct($translator, $data, $rules, $messages = [])
    {
        parent::__construct($translator, $data, $rules, $messages);
        $this->setFriendlyAttributeNames();
    }

    /**
     * Custom validation that rejects any input that has a value.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateReject($attribute, $value)
    {
        if (is_null($value))
        {
            return true;
        }
        elseif (is_string($value) && $value === '')
        {
            return true;
        }
        elseif ((is_array($value) || $value instanceof Countable) && count($value) < 1)
        {
            return true;
        }
        elseif ($value instanceof File)
        {
            return (string) $value->getPath() == '';
        }
        return false;
    }

    /**
     * Set user-friendly names for the randomized attributes.
     *
     * @return void
     */
    protected function setFriendlyAttributeNames()
    {
        $friendlyAttributes = [];
        $randomizedAttributes = Defender::all();

        foreach ($randomizedAttributes as $key => $attribute)
        {
            if ($key == 'bait' || $key == 'bait-2') continue;

            $friendlyAttributes[$attribute] = $key;
        }

        $this->setAttributeNames($friendlyAttributes);
    }
}
