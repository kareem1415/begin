<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = "phone";
    protected $fillable = ['code', 'phone'];
    protected $hidden = ['user_id'];
    public $timestamps = false;

    ######################## Begin relations ####################

    public function user()
    {
        return $this -> belongsTo('App\User', 'user_id');
    }

    ######################## End relations ######################


}
