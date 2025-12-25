<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    protected $fillable = [
        'period',
        'user_id',
        'animal_id',
        'qty',
        'nominal',
        'address',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function animal()
    {
        return $this->hasOne('App\Models\Animal', 'id', 'animal_id');
    }

    public function savingdetail()
    {
        return $this->hasMany('App\Models\SavingDetail', 'saving_id', 'id');
    }

    public function savingdetails()
    {
        return $this->belongsTo('App\Models\SavingDetail');
    }
}
