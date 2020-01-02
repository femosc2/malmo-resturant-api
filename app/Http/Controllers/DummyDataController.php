<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\ItemReview;
use App\ItemImage;
use App\Resturant;
use App\ResturantReview;
use App\ResturantImage;

class DummyDataController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/dummydata",
     *     description="Create a new item image",
     *     tags={"Dummy Data"},
     *     @OA\Response(response="default", description="Create Dummy data for testing purposes"),
     * )
     */
    public function create(Request $request)
    {
        $item = new Item;
        $item_image = new ItemImage;
        $resturant = new Resturant;
        $resturant_image = new ResturantImage;

        $resturant->name = 'Felix Resturang';
        $resturant->location = 'Lilla Varvsgatan 41g';

        $resturant_image->image = 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&w=1000&q=80';
        $resturant_image->resturant_id = 1;

        $resturant_image->save();

        $resturant->save();

        $item->name = 'Pasta and Meatball';
        $item->type = 'Pasta';
        $item->price = '89';
        $item->resturant_id = 1;

        $item->save();

        $item_image->image = 'https://i.redd.it/3yeip55792r21.jpg';
        $item_image->item_id = 1;

        $item_image->save();

        $jsonResponse = [];
     }
}
