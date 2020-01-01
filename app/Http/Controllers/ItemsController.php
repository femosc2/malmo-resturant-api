<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resturant;
use App\Item;
use App\ApiToken;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

      /**
     * @OA\Get(
     *     path="/api/items",
     *     description="Get all Items",
     *     tags={"Items"},
     *     @OA\Response(response="default", description="Get all Items")
     * )
     */
    public function index()
    {
        $items = Item::all();

        if (sizeof($items) == 0) {
            return abort(400, 'There exists no items.');
        }

        $jsonResponse = [];

        foreach($items as $item) {
            $resturant_name = Resturant::find($item->resturant_id)->name;
            array_push($jsonResponse, [
                'id' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'price' => $item->price,
                'rating' => $item->rating,
                'resturant' => $resturant_name,
                'reports' => $item->reports,
                'is_bad' => $item->is_bad,
                ]);
        }
        return $jsonResponse;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     /** @OA\Post(
     *     path="/api/items/new",
     *     tags={"Items"},
     *     description="Create a new Item",
     *     @OA\Response(response="default", description="Create a new resturant"),
     * @OA\Parameter(
     *         description="Name of Item",
     *         name="name",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="file"
     *         ),
     *     ),
     * @OA\Parameter(
     *         description="Type of Item",
     *         name="type",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="file"
     *         ),
     *     ),
     * @OA\Parameter(
     *         description="Price of Item",
     *         name="price",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="file"
     *         ),
     *     ),
     * @OA\Parameter(
     *         description="Id of Resturant it belongs to.",
     *         name="resturant_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="file"
     *         ),
     *     ),
     *   @OA\Parameter(
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
        $item = new Item;

        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'price' => 'required',
            'resturant_id' => 'required',
        ]);

        $item->name = $request->input('name');
        $item->type = $request->input('type');
        $item->price = $request->input('price');
        $item->resturant_id = $request->input('resturant_id');

        $item->save();

        $jsonResponse = [];

        array_push($jsonResponse, [
            'name' => $item->name,
            'type' => $item->type,
            'price' => $item->price,
            'resturant' => $item->resturant_id,
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
        *     path="/api/items/{id}",
        *     description="Get a specific item",
        *     tags={"Items"},
        *     @OA\Response(response="default", description="Get a specific item"),
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
        * )
        */
    public function show(Request $request)
    {
        $item = Item::find($request->id);

        if ($item == null) {
            return abort(400, 'There exists no item with this ID.');
        }

        $resturant_name = Resturant::find($item->resturant_id)->name;

        $jsonResponse = [
            'id' => $item->id,
            'name' => $item->name,
            'type' => $item->type,
            'price' => $item->price,
            'rating' => $item->rating,
            'resturant' => $resturant_name,
            'reports' => $item->reports,
            'is_bad' => $item->is_bad,
        ];

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

     /** @OA\Delete(
        *     path="/api/items/delete/{id}",
        *     description="Delete a specific item",
        *     tags={"Items"},
        *     @OA\Response(response="default", description="Delete a specific item"),
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
    public function destroy(Request $request)
    {
        $level_2_tokens = ApiToken::where('level', '=', 2)->pluck('key')->toArray();

        if (!in_array($request->input('token'),  $level_2_tokens)) {
            return abort(401, 'Not authorized');
        }

        Item::find($request->id)->delete();

        return ['Item deleted'];

    }

    /** @OA\Put(
        *     path="/api/items/report/{id}",
        *     description="Report an item",
        *     tags={"Items"},
        *     @OA\Response(response="default", description="Report an item"),
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
        *   @OA\Parameter(
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

        $item = Item::find($request->id);
        if ($item == null) {
            return abort(400, 'There exists no item with this id');
        }
        $item->reports++;

        if($item->reports > 20) {
            $item->is_bad = True;
        }

        $item->update();

        $jsonResponse = [];

        array_push($jsonResponse, [
            'reports' => $item->reports,
            'is_bad' => $item->is_bad,
        ]);

        return $jsonResponse;
    }


    /** @OA\Put(
        *     path="/api/items/unreport/{id}",
        *     description="unreport an item",
        *     tags={"Items"},
        *     @OA\Response(response="default", description="unreport an item"),
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
        *   @OA\Parameter(
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

        $item = Item::find($request->id);
        if ($item == null) {
            return abort(400, 'There exists no item with this id');
        }
        if(!$item->reports <= 0) {
            $item->reports--;
        } else {
            return abort(400, 'This item does not have any reports');
        }

        if($item->reports <= 20) {
            $item->is_bad = False;
        };

        $item->update();

        $jsonResponse = [];

        array_push($jsonResponse, [
            'reports' => $item->reports,
            'is_bad' => $item->is_bad,
        ]);

        return $jsonResponse;
    }
}
