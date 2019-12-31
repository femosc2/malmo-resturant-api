<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ResturantImage;

class ResturantImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Post(
     *     path="/api/resturantimages/new",
     *     description="Create a new resturant image",
     *     tags={"Resturant Images"},
     *     @OA\Response(response="default", description="Create a new resturant image"),
     * @OA\Parameter(
     *         description="Url to an image",
     *         name="image",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="file"
     *         ),
     *     ),
     *  @OA\Parameter(
     *         description="resturant_id",
     *         name="resturant_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="file"
     *         ),
     *     ),
     * )
     */
    public function create(Request $request)
    {
        $resturant_image = new ResturantImage;

        $resturant_image->image = $request->input('image');
        $resturant_image->resturant_id = $request->input('resturant_id');

        $resturant_image->save();
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
    public function show($id)
    {
        //
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
        *     path="/api/resturantimages/report/{id}",
        *     description="Report an resturant image",
        *     tags={"Resturant Images"},
        *     @OA\Response(response="default", description="Report an Resturant image"),
        * @OA\Parameter(
        *         description="Id of Resturant image",
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
            $resturant_image= ResturantImage::find($request->id);
            $resturant_image->reports++;

            if($resturant_image->reports > 20) {
                $resturant_image->is_bad = True;
            }

            $resturant_review->update();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'reports' => $resturant_image->reports,
                'is_bad' => $resturant_image->is_bad,
            ]);

            return $jsonResponse;
        }


        /** @OA\Put(
            *     path="/api/resturantimages/unreport/{id}",
            *     description="unreport an item review",
            *     tags={"Resturant Reviews"},
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
            $item_image = ItemImage::find($request->id);

            if(!$item_image->reports <= 0) {
                $item_image->reports--;
            } else {
                return abort(400, 'This item review does not have any reports');
            }

            if($item_image->reports <= 20) {
                $item_image->is_bad = False;
            };

            $item_image->update();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'reports' => $item_image->reports,
                'is_bad' => $item_image->is_bad,
            ]);

            return $jsonResponse;
        }
}
