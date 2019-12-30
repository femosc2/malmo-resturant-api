<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resturant;
use App\Item;
use App\ItemReview;

class ItemReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

      /**
     * @OA\Get(
     *     path="/api/itemreviews",
     *     tags={"Item Reviews"},
     *     description="Get all Item Reviews",
     *     @OA\Response(response="default", description="Get all Item Reviews")
     * )
     */

    public function index()
    {
        $item_reviews = ItemReview::all();

        $jsonResponse = [];

        foreach($item_reviews as $item_review) {
            $item = Item::find($item_review->item_id);
            $resturant_name = Resturant::find($item->resturant_id)->name;
            array_push($jsonResponse, [
                    'reviewer' => $item_review->reviewer,
                    'review' => $item_review->review,
                    'rating' => $item_review->rating,
                    'item_id' => $item_review->item_id,
                    'item_name' => $item->name,
                    'resturant' => $resturant_name,
                    ]);
        }

        return $jsonResponse;
    }

    /** @OA\Post(
     *     path="/api/itemreviews/new",
     *     description="Create a new Item Review",
     *     tags={"Item Reviews"},
     *     @OA\Response(response="default", description="Create a new Item Review"),
     * @OA\Parameter(
     *         description="Name of Reviewer",
     *         name="reviewer",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="file"
     *         ),
     *     ),
     * @OA\Parameter(
     *         description="Rating of Item",
     *         name="rating",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="file",
     *         ),
     *     ),
     * @OA\Parameter(
     *         description="Review of Item",
     *         name="review",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="file"
     *         ),
     *     ),
     * @OA\Parameter(
     *         description="Id of Item it belongs to.",
     *         name="item_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="file"
     *         ),
     *     ),
     *  )
     */
    public function create(Request $request)
    {
        $item_review = new ItemReview;

        $item_review->reviewer = $request->input('reviewer');
        $item_review->rating = $request->input('rating');
        $item_review->review = $request->input('review');
        $item_review->item_id = $request->input('item_id');

        $item_review->save();

        $item_reviews = ItemReview::where('item_id', $item_review->item_id)->get();
        $item_rating = 0;
        foreach($item_reviews as $item_review) {
            $item_rating = $item_rating + $item_review->rating;
        }
        $item_rating = $item_rating/sizeof($item_reviews);

        $item = Item::find($item_review->item_id);


        $item->rating = $item_rating;

        echo $item;


        $item->update();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     /** @OA\Get(
        *     path="/api/itemreviews/{id}",
        *     description="Get a specific item review",
        *     tags={"Item Reviews"},
        *     @OA\Response(response="default", description="Get a specific item review"),
        * @OA\Parameter(
        *         description="Id of item review",
        *         name="id",
        *         in="query",
        *         required=true,
        *         @OA\Schema(
        *             type="integer",
        *             format="file"
        *         ),
        *     ),
        * )
        */
    public function show(Request $request)
    {
        $item_review = ItemReview::find($request->id);
        $jsonResponse = [];
        $item = Item::find($item_review->item_id);
        $resturant_name = Resturant::find($item->resturant_id)->name;

        array_push($jsonResponse, [
                'reviwer' => $item_review->reviewer,
                'review' => $item_review->review,
                'rating' => $item_review->rating,
                'item_id' => $item_review->item_id,
                'item_name' => $item->name,
                'resturant' => $resturant_name,
                ]);

        return $jsonResponse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
