<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResturantImage extends Model
{
    public function resturant() {
        return $this->belongsTo('App\Resturant');
    }
}
