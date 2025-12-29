<?php

namespace App\Models\IAM;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    protected $table = 'iam.app_user';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'unit_id',
        'email',
        'full_name',
        'status',
        'created_at',
    ];
}
