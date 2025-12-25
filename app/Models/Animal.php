<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $fillable = [
        'name',
        'uom',
    ];

    public function savings()
    {
        return $this->belongsTo('App\Models\Saving');
    }
}
