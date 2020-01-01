<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemImage;
use App\ApiToken;

class ItemImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /** @OA\Get(
        *     path="/api/itemimages",
        *     tags={"Item Images"},
        *     description="Get all Item Images",
        *     @OA\Response(response="default", description="Get all Item Images")
        * )
    */
    public function index()
    {
        $item_images = ItemImage::all();
        if ($item_images == null) {
            return abort(400, 'There exists no Item images');
        }

        $jsonResponse = [];

        foreach($item_images as $item_image) {
            array_push($jsonResponse, [
                    'id' => $item_image->id,
                    'image' => $item_image->image,
                    'item_id' => $item_image->item_id,
                    'reports' => $item_image->reports,
                    'is_bad' => $item_image->is_bad,
                    ]);
        }

        return $jsonResponse;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Post(
     *     path="/api/itemimages/new",
     *     description="Create a new item image",
     *     tags={"Item Images"},
     *     @OA\Response(response="default", description="Create a new item image"),
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
     *         description="item_id",
     *         name="item_id",
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
    public function create(Request $request)
    {

        $tokens = ApiToken::all()->pluck('key')->toArray();

        if (!in_array($request->input('token'),  $tokens)) {
            return abort(401, 'Not authorized');
        }

        $item_image = new ItemImage;

        $item_image->image = $request->input('image');
        $item_image->item_id = $request->input('item_id');

        $item_image->save();

        $jsonResponse = [];

        array_push($jsonResponse, [
            'image' => $request->input('image'),
            'item_id' => $request->input('item_id'),
        ]);

        return $jsonResponse;
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
        *     path="/api/itemimages/{id}",
        *     description="Get a specific item image",
        *     tags={"Item Images"},
        *     @OA\Response(response="default", description="Get a specific item image"),
        * @OA\Parameter(
        *         description="Id of item image",
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

            $item_image = ItemImage::find($request->id);
            if ($item_image == null) {
                return abort(400, 'There exists no item image with this id.');
            }
            $jsonResponse = [];

            array_push($jsonResponse, [
                'id' => $item_image->id,
                'image' => $item_image->image,
                'item_id' => $item_image->item_id,
                'reports' => $item_image->reports,
                'is_bad' => $item_image->is_bad,
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

   /** @OA\Delete(
        *     path="/api/itemimages/delete/{id}",
        *     description="Delete a specific item",
        *     tags={"Item Images"},
        *     @OA\Response(response="default", description="Delete a specific item images"),
        * @OA\Parameter(
        *         description="Id of item images",
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

            ItemImage::find($request->id)->delete();

            return ['Item image deleted'];

        }

    /** @OA\Put(
        *     path="/api/itemimages/report/{id}",
        *     description="Report an item image",
        *     tags={"Item Images"},
        *     @OA\Response(response="default", description="Report an item review"),
        * @OA\Parameter(
        *         description="Id of item image",
        *         name="id",
        *         in="query",
        *         required=true,
        *         @OA\Schema(
        *             type="integer",
        *             format="file"
        *         ),
        *     ),
        * @OA\Parameter(
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
            $item_image = ItemImage::find($request->id);
            if ($item_image == null) {
                return abort(400, 'There exists no item image with this id.');
            }

            $item_image->reports++;

            if($item_image->reports > 20) {
                $item_image->is_bad = True;
            }

            $item_image->update();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'reports' => $item_image->reports,
                'is_bad' => $item_image->is_bad,
            ]);

            return $jsonResponse;
        }


        /** @OA\Put(
            *     path="/api/itemimages/unreport/{id}",
            *     description="unreport an item review",
            *     tags={"Item Images"},
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
            * @OA\Parameter(
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

            $item_image = ItemImage::find($request->id);
            if ($item_image == null) {
                return abort(400, 'There exists no item image with this id.');
            }

            if(!$item_image->reports <= 0) {
                $item_image->reports--;
            } else {
                return abort(400, 'This item image does not have any reports');
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
