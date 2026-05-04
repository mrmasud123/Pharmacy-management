<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class UniqueEmailAcrossDB implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
    
    public function passes($attribute, $value)
    {
        // Get all tables
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tables as $table) {
            // Skip tables without 'email' column
            if (!Schema::hasColumn($table, 'email')) {
                continue;
            }

            // Check if the email exists
            if (DB::table($table)->where('email', $value)->exists()) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'The :attribute has already been taken.';
    }
}
