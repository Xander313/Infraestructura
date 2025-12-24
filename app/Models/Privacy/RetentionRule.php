<?php

namespace App\Models\Privacy;

use Illuminate\Database\Eloquent\Model;

class RetentionRule extends Model
{
    protected $table = 'privacy.retention_rule';
    protected $primaryKey = 'retention_id';
    public $timestamps = false;

    protected $fillable = [
        'pa_id',
        'retention_period_days',
        'trigger_event',
        'disposal_method',
        'legal_hold_flag',
    ];
}
