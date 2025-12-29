<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Org extends Model
{
    protected $table = 'core.org';
    protected $primaryKey = 'org_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'ruc',
        'industry',
    ];
}
