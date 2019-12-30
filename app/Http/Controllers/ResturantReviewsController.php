<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resturant;
use App\Item;
use App\ResturantReview;

class ResturantReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

      /**
     * @OA\Get(
     *     path="/api/resturantreviews",
     *     tags={"Resturant Reviews"},
     *     description="Get all Resturant Reviews",
     *     @OA\Response(response="default", description="Get all Resturant Reviews")
     * )
     */

    public function index()
    {
        $resturant_review = ResturantReview::all();

        $jsonResponse = [];

        foreach($resturant_reviews as $resturant_review) {
            $resturant_name = Resturant::find($resturant_review->resturant_id)->name;
            array_push($jsonResponse, [
                    'reviewer' => $resturant_review->reviewer,
                    'review' => $resturant_review->review,
                    'rating' => $resturant_review->rating,
                    'resturant_id' => $resturant_review->resturant_id,
                    'resturant' => $resturant_name,
                    ]);
        }

        return $jsonResponse;
    }

    /** @OA\Post(
     *     path="/api/resturantreviews/new",
     *     tags={"Resturant Reviews"},
     *     description="Create a new Resturant Review",
     *     @OA\Response(response="default", description="Create a new Resturant Review"),
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
     *         description="Rating of resturant",
     *         name="rating",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="file",
     *         ),
     *     ),
     * @OA\Parameter(
     *         description="Review of Resturant",
     *         name="review",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="file"
     *         ),
     *     ),
     * @OA\Parameter(
     *         description="Id of resturant it belongs to.",
     *         name="resturant_id",
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
        $resturant_review = new ResturantReview;

        $resturant_review->reviewer = $request->input('reviewer');
        $resturant_review->rating = $request->input('rating');
        $resturant_review->review = $request->input('review');
        $resturant_review->resturant_id = $request->input('resturant_id');

        $resturant_review->save();

        $resturant_reviews = ResturantReview::where('resturant_id', $resturant_review->resturant_id)->get();
        $resturant_rating = 0;
        foreach($resturant_reviews as $resturant_review) {
            $resturant_rating = $resturant_rating + $resturant_review->rating;
        }
        $resturant_rating = $resturant_rating/sizeof($resturant_reviews);

        $resturant = Resturant::find($resturant_review->resturant_id);


        $resturant->rating = $resturant_rating;

        echo $resturant;


        $resturant->update();

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
        *     path="/api/resturantreviews/{id}",
        *     description="Get a specific resturant review",
        *     tags={"Resturant Reviews"},
        *     @OA\Response(response="default", description="Get a specific resturant review"),
        * @OA\Parameter(
        *         description="Id of resturant review",
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
        $resturant_review = ResturantReview::find($request->id);
        $jsonResponse = [];
        $resturant_name = Resturant::find($resturant_review->resturant_id)->name;

        array_push($jsonResponse, [
                'reviwer' => $resturant_review->reviewer,
                'review' => $resturant_review->review,
                'rating' => $resturant_review->rating,
                'resturant_id' => $resturant_review->resturant_id,
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
