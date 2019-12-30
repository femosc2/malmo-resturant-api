<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Resturant extends Model
{
    public function item() {
        return $this->hasMany('App\Item');
    }

    public function resturant_review() {
        return $this->hasMany('App\ResturantReview');
    }
}
