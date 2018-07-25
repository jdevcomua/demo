<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class BirthdayRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $temp = explode('-', $value);

        if (strlen($temp[2]) > 4 || strlen($temp[1]) > 2 || strlen($temp[0]) > 2) {
            return false;
        }

        $value = Carbon::createFromFormat('d-m-Y', $value);
        if (!$value) {
            return false;
        }
        if ($value > Carbon::now() || $value < Carbon::today()->subYears(100)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute should be a correct data!';
    }
}
