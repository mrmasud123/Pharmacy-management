<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Model
{
    use HasRoles, SoftDeletes;
    protected string $guard_name = 'web';
    protected $guarded = [];
}
