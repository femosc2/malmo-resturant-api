<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resturant;
use App\Item;
use App\ResturantReview;
use App\ApiToken;

class ResturantReviewsController extends Controller
{
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
        $resturant_reviews = ResturantReview::all();

        if (sizeof($resturant_reviews) == 0) {
            return abort(400, 'There exists no resturant reviews');
        };

        $jsonResponse = [];

        foreach($resturant_reviews as $resturant_review) {
            $resturant_name = Resturant::find($resturant_review->resturant_id)->name;
            array_push($jsonResponse, [
                    'id' => $resturant_review->id,
                    'reviewer' => $resturant_review->reviewer,
                    'review' => $resturant_review->review,
                    'rating' => $resturant_review->rating,
                    'resturant_id' => $resturant_review->resturant_id,
                    'resturant' => $resturant_name,
                    'reports' => $resturant_review->reports,
                    'is_bad' => $resturant_review->is_bad,
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
     *  @OA\Parameter(
     *         description="Api Token",
     *         name="token",
     *         in="query",
     *         required=true,
    *         @OA\Schema(
    *             type="string",
    *             format="file"
    *         ),
    *     ),
     *  )
     */
    public function create(Request $request)
    {
        $tokens = ApiToken::all()->pluck('key')->toArray();

            if (!in_array($request->input('token'),  $tokens)) {
                return abort(401, 'Not authorized');
            }

        $resturant_review = new ResturantReview;

        $request->validate([
            'reviewer' => 'required',
            'rating' => 'required',
            'review' => 'required',
            'resturant_id' => 'required',
        ]);

        $resturant_review->reviewer = $request->input('reviewer');
        $resturant_review->rating = $request->input('rating');
        $resturant_review->review = $request->input('review');
        $resturant_review->resturant_id = $request->input('resturant_id');

        if(strlen($resturant_review->reviewer) > 30 || $resturant_review->rating > 10 || $resturant_review->rating < 0) {
            return abort(400, 'Name can not be longer than 30 characters and rating can not be higher than 10 or lower than 0');
        } else {
            $resturant_review->save();

            $resturant_reviews = ResturantReview::where('resturant_id', $resturant_review->resturant_id)->get();
            $resturant_rating = 0;
            foreach($resturant_reviews as $resturant_review) {
                $resturant_rating = $resturant_rating + $resturant_review->rating;
            }
            $resturant_rating = $resturant_rating/sizeof($resturant_reviews);
            $resturant = Resturant::find($resturant_review->resturant_id);
            $resturant->rating = $resturant_rating;
            $resturant->update();

            $jsonResponse = [];

        array_push($jsonResponse, [
            'reviwer' => $resturant_review->reviewer,
            'review' => $resturant_review->review,
            'rating' => $resturant_review->rating,
            'resturant_id' => $resturant_review->resturant_id,
            ]);

        return $jsonResponse;
    }
    }

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
        if ($resturant_review == null) {
            return abort(400, 'There exists no resturant review with this id');
        };

        $jsonResponse = [];
        $resturant_name = Resturant::find($resturant_review->resturant_id)->name;

        array_push($jsonResponse, [
                'id' => $resturant_review->id,
                'reviwer' => $resturant_review->reviewer,
                'review' => $resturant_review->review,
                'rating' => $resturant_review->rating,
                'resturant_id' => $resturant_review->resturant_id,
                'resturant' => $resturant_name,
                'reports' => $resturant_review->reports,
                'is_bad' => $resturant_review->is_bad,
                ]);

        return $jsonResponse;
    }

    /**
    *@OA\Delete(
        *     path="/api/resturantreviews/delete/{id}",
        *     description="Delete a specific resturant review",
        *     tags={"Resturant Reviews"},
        *     @OA\Response(response="default", description="Delete a specific resturant reviews"),
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
        *  @OA\Parameter(
     *         description="Api Token",
     *         name="token",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="file"
     *         ),
     *     ),
        * )
        */
        public function destroy(Request $request)
        {
            $level_2_tokens = ApiToken::where('level', '=', 2)->pluck('key')->toArray();

            if (!in_array($request->input('token'),  $level_2_tokens)) {
                return abort(401, 'Not authorized');
            }

            ResturantReview::find($request->id)->delete();

            return ['Resturant Review Deleted'];

        }

    /** @OA\Put(
        *     path="/api/resturantreviews/report/{id}",
        *     description="Report a Resturant Review",
        *     tags={"Resturant Reviews"},
        *     @OA\Response(response="default", description="Report an item"),
        * @OA\Parameter(
        *         description="Id of Resturant Review",
        *         name="id",
        *         in="query",
        *         required=true,
        *         @OA\Schema(
        *             type="integer",
        *             format="file"
        *         ),
        *     ),
        *  @OA\Parameter(
        *         description="Api Token",
        *         name="token",
        *         in="query",
        *         required=true,
        *         @OA\Schema(
        *             type="string",
        *             format="file"
        *         ),
        *     ),
        * )
        */
        public function report(Request $request)
        {
            $tokens = ApiToken::all()->pluck('key')->toArray();

            if (!in_array($request->input('token'),  $tokens)) {
                return abort(401, 'Not authorized');
            }

            $resturant_review = ResturantReview::find($request->id);

            if ($resturant_review == null) {
                return abort(400, 'There exists no resturant review with this id');
            };

            $resturant_review->reports++;

            if($resturant_review->reports > 20) {
                $resturant_review->is_bad = True;
            }

            $resturant_review->update();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'reports' => $resturant_review->reports,
                'is_bad' => $resturant_review->is_bad,
            ]);

            return $jsonResponse;
        }


        /** @OA\Put(
            *     path="/api/resturantreviews/unreport/{id}",
            *     description="unreport an Resturant Review",
            *     tags={"Resturant Reviews"},
            *     @OA\Response(response="default", description="unreport an Resturant Review"),
            * @OA\Parameter(
            *         description="Id of item",
            *         name="id",
            *         in="query",
            *         required=true,
            *         @OA\Schema(
            *             type="integer",
            *             format="file"
            *         ),
            *     ),
            *  @OA\Parameter(
            *         description="Api Token",
            *         name="token",
            *         in="query",
            *         required=true,
            *         @OA\Schema(
            *             type="string",
            *             format="file"
            *         ),
            *     ),
            * )
            */
        public function unreport(Request $request)
        {
            $tokens = ApiToken::all()->pluck('key')->toArray();

            if (!in_array($request->input('token'),  $tokens)) {
                return abort(401, 'Not authorized');
            }

            $resturant_review = ResturantReview::find($request->id);
            if ($resturant_review == null) {
                return abort(400, 'There exists no resturant review with this id.');
            };
            $resturant_review->reports--;

            if($resturant_review->reports <= 20) {
                $resturant_review->is_bad = False;
            } else {
                return abort(400, 'This review does not have any reports');
            }

            $resturant_review->update();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'reports' => $resturant_review->reports,
                'is_bad' => $resturant_review->is_bad,
            ]);

            return $jsonResponse;
        }
}
