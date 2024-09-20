<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailExistsForRole implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string = null): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $roleId;

    public function __construct($roleId)
    {
        $this->roleId = $roleId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!User::where('email', $value)->where('role_id', $this->roleId)->exists()) {
            $fail('The selected email does not exist for the specified role.');
        }
    }
}
