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
        if (sizeof($item_reviews) == 0) {
            return abort(400, 'There exists no item reviews');
        }

        $jsonResponse = [];

        foreach($item_reviews as $item_review) {
            $item = Item::find($item_review->item_id);
            $resturant_name = Resturant::find($item->resturant_id)->name;
            array_push($jsonResponse, [
                    'id' => $item_review->id,
                    'reviewer' => $item_review->reviewer,
                    'review' => $item_review->review,
                    'rating' => $item_review->rating,
                    'item_id' => $item_review->item_id,
                    'item_name' => $item->name,
                    'resturant' => $resturant_name,
                    'reports' => $item_review->reports,
                    'is_bad' => $item_review->is_bad,
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
     *         description="Rating of Item (0-10)",
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

        if(strlen($item_review->reviewer) > 30 || $item_review->rating > 10 || $item_review->rating < 0) {
            return abort(400, 'Name can not be longer than 30 characters and rating can not be higher than 10 or lower than 0');
        } else {
            $item_review->save();

            $item_reviews = ItemReview::where('item_id', $item_review->item_id)->get();
            $item_rating = 0;
            foreach($item_reviews as $item_review) {
                $item_rating = $item_rating + $item_review->rating;
            }
            $item_rating = $item_rating/sizeof($item_reviews);
            $item = Item::find($item_review->item_id);
            $item->rating = $item_rating;
            $item->update();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'reviwer' => $item_review->reviewer,
                'review' => $item_review->review,
                'rating' => $item_review->rating,
                'item_id' => $item_review->item_id,
                ]);

            return $jsonResponse;
        }
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
                'id' => $item_review->id,
                'reviwer' => $item_review->reviewer,
                'review' => $item_review->review,
                'rating' => $item_review->rating,
                'item_id' => $item_review->item_id,
                'item_name' => $item->name,
                'resturant' => $resturant_name,
                'reports' => $item_review->reports,
                'is_bad' => $item_review->is_bad,
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

    /** @OA\Put(
        *     path="/api/itemreviews/report/{id}",
        *     description="Report an item review",
        *     tags={"Item Reviews"},
        *     @OA\Response(response="default", description="Report an item review"),
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
        public function report(Request $request)
        {
            $item_review = ItemReview::find($request->id);
            if ($item_review == null) {
                return abort(400, 'There exists no Item Review with this id');
            }
            $item_review->reports++;

            if($item_review->reports > 20) {
                $item_review->is_bad = True;
            }

            $item_review->update();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'reports' => $item_review->reports,
                'is_bad' => $item_review->is_bad,
            ]);

            return $jsonResponse;
        }


        /** @OA\Put(
            *     path="/api/itemreviews/unreport/{id}",
            *     description="unreport an item review",
            *     tags={"Item Reviews"},
            *     @OA\Response(response="default", description="unreport an item review"),
            * @OA\Parameter(
            *         description="Id of item reviews",
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
        public function unreport(Request $request)
        {
            $item_review = ItemReview::find($request->id);
            if ($item_review == null) {
                return abort(400, 'There exists no Item Review with this id');
            }

            if(!$item_review->reports <= 0) {
                $item_review->reports--;
            } else {
                return abort(400, 'This item review does not have any reports');
            }

            if($item_review->reports <= 20) {
                $item_review->is_bad = False;
            };

            $item_review->update();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'reports' => $item_review->reports,
                'is_bad' => $item_review->is_bad,
            ]);

            return $jsonResponse;
        }

}
