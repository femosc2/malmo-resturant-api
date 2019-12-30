<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resturant;
use App\Item;

class ResturantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

      /**
     * @OA\Get(
     *     path="/api/resturants",
     *      tags={"Resturants"},
     *     description="Get all Resturants",
     *     @OA\Response(response="default", description="Get all Resturants")
     * )
     */
    public function index()
    {
            $resturants = Resturant::all();

            $jsonResponse = [];

            foreach($resturants as $resturant) {
                array_push($jsonResponse, [
                        'name' => $resturant->name,
                        'location' => $resturant->location,
                        'rating' => $resturant->rating,
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
     *     path="/api/resturants/new",
     *     description="Create a new resturant",
     *     tags={"Resturants"},
     *     @OA\Response(response="default", description="Create a new resturant"),
     * @OA\Parameter(
     *         description="Name of Resturant",
     *         name="name",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="file"
     *         ),
     *     ),
     * @OA\Parameter(
     *         description="Location of Resturant",
     *         name="location",
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
        $resturant = new Resturant;

        $resturant->name = $request->input('name');
        $resturant->location = $request->input('location');

        $request_location = str_replace(" ", "%20", $resturant->location);
        $response = file_get_contents('https://maps.google.com/maps/api/geocode/json?address='. $request_location . '&key=' . env('GOOGLE_API_KEY') );

        $response = json_decode($response);

        if(($response->results[0]->address_components[1]->long_name == 'Malmö')
        || ($response->results[0]->address_components[2]->long_name == 'Malmö')
        || ($response->results[0]->address_components[3]->long_name == 'Malmö') ) {
            $resturant->save();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'name' => $resturant->name,
                'location' => $resturant->location,
                ]);

            return $jsonResponse;
        } else {
            return 'This resturant is not located in Malmö';
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
        *     path="/api/resturants/{id}",
        *     description="Get a specific resturant",
        *     tags={"Resturants"},
        *     @OA\Response(response="default", description="Get a specific resturant"),
        * @OA\Parameter(
        *         description="Id of resturant",
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

        $resturant = Resturant::find($request->id);
        $jsonResponse = [];

        array_push($jsonResponse, [
            'name' => $resturant->name,
            'location' => $resturant->location,
            'rating' => $resturant->rating,
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
