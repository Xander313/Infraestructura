<?php

namespace App\Models\Privacy;

use Illuminate\Database\Eloquent\Model;

class ProcessingActivity extends Model
{
    protected $table = 'privacy.processing_activity';
    protected $primaryKey = 'pa_id';
    public $timestamps = false;

    protected $fillable = [
        'org_id',
        'owner_unit_id',
        'name',
    ];

    // N:M con categorías de datos
    public function dataCategories()
    {
        return $this->belongsToMany(
            DataCategory::class,
            'privacy.pa_data_category',
            'pa_id',
            'data_cat_id'
        )->withPivot('collection_source');
    }
/*
    // 1:N Retención
    public function retentionRules()
    {
        return $this->hasMany(RetentionRule::class, 'pa_id');
    }

    // 1:N Transferencias
    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'pa_id');
    }*/
}
