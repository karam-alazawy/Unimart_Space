<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'office_name',
        'office_province',
        'addedByUserId',
        'updated_at',
        'created_at',
       ];
}
