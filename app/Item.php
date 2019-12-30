<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function resturant() {
        return $this->belongsTo('App\Resturant');
    }

    public function item_review() {
        return $this->hasMany('App\ItemReview');
    }
}
