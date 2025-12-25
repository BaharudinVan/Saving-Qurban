<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingDetail extends Model
{
    protected $fillable = [
        'date',
        'saving_id',
        'amount',
        'evidence'
    ];

    public function savings()
    {
        return $this->hasOne('App\Models\Saving', 'id', 'saving_id');
    }

    public function savingss()
    {
        return $this->belongsTo('App\Models\Saving');
    }
}
