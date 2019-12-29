<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resturant;
use App\Item;

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
     *     @OA\Response(response="default", description="Get all Items")
     * )
     */
    public function index()
    {
        $items = Item::all();

        $jsonResponse = [];

        foreach($items as $item) {
            $resturant_name = Resturant::find($item->resturant_id)->name;
            array_push($jsonResponse, [
                    'name' => $item->name,
                    'type' => $item->type,
                    'price' => $item->price,
                    'rating' => $item->rating,
                    'resturant' => $resturant_name,
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
     *  )
     */
    public function create(Request $request)
    {
        $item = new Item;

        $item->name = $request->input('name');
        $item->type = $request->input('type');
        $item->price = $request->input('price');
        $item->resturant_id = $request->input('resturant_id');

        $item->save();
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
        $resturant_name = Resturant::find($item->resturant_id)->name;

        $jsonResponse = [
            'name' => $item->name,
            'type' => $item->type,
            'price' => $item->price,
            'rating' => $item->rating,
            'resturant' => $resturant_name,
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
    public function destroy($id)
    {
        //
    }
}
