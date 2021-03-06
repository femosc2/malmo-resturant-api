<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resturant;
use App\Item;
use App\ApiToken;

class ResturantsController extends Controller
{
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
            if (sizeof($resturants) == 0) {
                return abort(400, "There is no resturants in the database.");
            }

            $jsonResponse = [];


            foreach($resturants as $resturant) {
                array_push($jsonResponse, [
                        'id' => $resturant->id,
                        'name' => $resturant->name,
                        'location' => $resturant->location,
                        'lat' => $resturant->lat,
                        'lng' => $resturant->lng,
                        'rating' => $resturant->rating,
                        'reports' => $resturant->reports,
                        'is_bad' => $resturant->is_bad,
                        ]);
            }
            return $jsonResponse;
    }

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
    public function create(Request $request)
    {
        $tokens = ApiToken::all()->pluck('key')->toArray();

            if (!in_array($request->input('token'),  $tokens)) {
                return abort(401, 'Not authorized');
            }

        $resturant = new Resturant;

        $request->validate([
            'name' => 'required',
            'location' => 'required',
        ]);

        $resturant->name = $request->input('name');
        $resturant->location = $request->input('location');

        $request_location = str_replace(" ", "%20", $resturant->location);
        $response = file_get_contents('https://maps.google.com/maps/api/geocode/json?address='. $request_location . '&key=' . env('GOOGLE_API_KEY') );

        $response = json_decode($response);

        print_r($response->results[0]->geometry->location->lat);

        if (($response->results[0]->address_components[1]->long_name == 'Malmö')
            || ($response->results[0]->address_components[2]->long_name == 'Malmö')
            || ($response->results[0]->address_components[3]->long_name == 'Malmö') ) {

            $resturant->location = $response->results[0]->formatted_address;
            $resturant->lat = $response->results[0]->geometry->location->lat;
            $resturant->lng = $response->results[0]->geometry->location->lng;

            $resturant->save();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'name' => $resturant->name,
                'location' => $resturant->location,
                'lat' => $resturant->lat,
                'lng' => $resturant->lng,
                ]);

            return $jsonResponse;
        } else {
            return abort(400, "This resturant is not located in Malmö.");
        }

    }

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

        if ($resturant == null) {
            return abort(400, 'There exists no resturant with this id.');
        }

        $jsonResponse = [];

        array_push($jsonResponse, [
            'id' => $resturant->id,
            'name' => $resturant->name,
            'location' => $resturant->location,
            'lat' => $resturant->lat,
            'lng' => $resturant->lng,
            'rating' => $resturant->rating,
            'reports' => $resturant->reports,
            'is_bad' => $resturant->is_bad,
            ]);

        return $jsonResponse;
    }

    /** @OA\Get(
        *     path="/api/resturants/by/name/{name}",
        *     description="Show resturants by name",
        *     tags={"Resturants"},
        *     @OA\Response(response="default", description="Get resturants by name"),
        * @OA\Parameter(
        *         description="Name of resturant",
        *         name="name",
        *         in="query",
        *         required=true,
        *         @OA\Schema(
        *             type="string",
        *             format="file"
        *         ),
        *     ),
        * )
        */
        public function show_by_name(Request $request)
        {
            $resturants = Resturant::where('name',$request->name)->get();

            if ($resturants == null || sizeof($resturants) == 0) {
                return abort(400, 'There exists no resturants with this name.');
            }

            $jsonResponse = [];

            foreach($resturants as $resturant) {
                array_push($jsonResponse, [
                'id' => $resturant->id,
                'name' => $resturant->name,
                'location' => $resturant->location,
                'lat' => $resturant->lat,
                'lng' => $resturant->lng,
                'rating' => $resturant->rating,
                'reports' => $resturant->reports,
                'is_bad' => $resturant->is_bad,
                ]);
            }
            return $jsonResponse;

        }

        /** @OA\Get(
        *     path="/api/resturants/by/location/{location}",
        *     description="Show resturants by location",
        *     tags={"Resturants"},
        *     @OA\Response(response="default", description="Get resturants by location"),
        * @OA\Parameter(
        *         description="Location of resturant",
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
        public function show_by_location(Request $request)
        {
            $resturants = Resturant::where('location',$request->location)->get();

            if ($resturants == null || sizeof($resturants) == 0) {
                return abort(400, 'There exists no resturants with this location.');
            }

            $jsonResponse = [];

            foreach($resturants as $resturant) {
                array_push($jsonResponse, [
                'id' => $resturant->id,
                'name' => $resturant->name,
                'location' => $resturant->location,
                'lat' => $resturant->lat,
                'lng' => $resturant->lng,
                'rating' => $resturant->rating,
                'reports' => $resturant->reports,
                'is_bad' => $resturant->is_bad,
                ]);
            }
            return $jsonResponse;

        }

    /** @OA\Delete(
        *     path="/api/resturants/delete/{id}",
        *     description="Delete a specific resturant image",
        *     tags={"Resturants"},
        *     @OA\Response(response="default", description="Delete a specific resturant"),
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

            Resturant::find($request->id)->delete();

            return ['Resturant Deleted'];

        }

    /** @OA\Put(
        *     path="/api/resturants/report/{id}",
        *     description="Report a Resturant",
        *     tags={"Resturants"},
        *     @OA\Response(response="default", description="Report a Resturant"),
        * @OA\Parameter(
        *         description="Id of Resturant",
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

            $resturant = Resturant::find($request->id);

            if ($resturant == null) {
                return abort(400, 'There exists no resturant with this id.');
            }

            $resturant->reports++;
            if($resturant->reports > 20) {
                $resturant->is_bad = True;
            }

            $resturant->update();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'reports' => $resturant->reports,
                'is_bad' => $resturant->is_bad,
            ]);

            return $jsonResponse;
        }


        /** @OA\Put(
            *     path="/api/resturants/unreport/{id}",
            *     description="unreport a Resturant",
            *     tags={"Resturants"},
            *     @OA\Response(response="default", description="unreport a Resturant"),
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

            $resturant = Resturant::find($request->id);

            if ($resturant == null) {
                return abort(400, 'This resturant does not exist.');
            }

            if(!$resturant->reports <= 0) {
                $resturant->reports--;
            } else {
                return abort(400, 'This resturant does not have any reports');
            }

            if($resturant->reports <= 20) {
                $resturant->is_bad = False;
            }

            $resturant->update();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'reports' => $resturant->reports,
                'is_bad' => $resturant->is_bad,
            ]);

            return $jsonResponse;
        }


}
