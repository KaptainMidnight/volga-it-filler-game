<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Ramsey\Uuid\Uuid as UuidValidator;

class UUID implements Rule
{
    /**
     * Validate UUID
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return UuidValidator::isValid($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Supplied :attribute is not valid UUID!';
    }
}
