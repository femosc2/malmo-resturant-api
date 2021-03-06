<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ResturantImage;
use App\ApiToken;

class ResturantImagesController extends Controller
{

    /** @OA\Get(
        *     path="/api/resturantimages",
        *     tags={"Resturant Images"},
        *     description="Get all Resturant Images",
        *     @OA\Response(response="default", description="Get all Resturant Images")
        * )
    */
    public function index()
    {
        $resturant_images = ResturantImage::all();

        $jsonResponse = [];

        foreach($resturant_images as $resturant_image) {
            array_push($jsonResponse, [
                    'id' => $resturant_image->id,
                    'image' => $resturant_image->image,
                    'resturant_id' => $resturant_image->resturant_id,
                    'reports' => $resturant_image->reports,
                    'is_bad' => $resturant_image->is_bad,
                    ]);
        }

        return $jsonResponse;
    }

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
     *
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

        $resturant_image = new ResturantImage;

        $request->validate([
            'image' => 'required',
            'resturant_id' => 'required',
        ]);

        $resturant_image->image = $request->input('image');
        $resturant_image->resturant_id = $request->input('resturant_id');

        $resturant_image->save();

        $jsonResponse = [];

        array_push($jsonResponse, [
            'image' => $request->input('image'),
            'resturant_id' => $request->input('resturant_id'),
        ]);

        return $jsonResponse;
     }

    /** @OA\Get(
        *     path="/api/resturantimages/{id}",
        *     description="Get a specific resturant image",
        *     tags={"Resturant Images"},
        *     @OA\Response(response="default", description="Get a specific resturant image"),
        * @OA\Parameter(
        *         description="Id of resturant image",
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
        public function show(Request $request)
        {

            $resturant_image = ResturantImage::find($request->id);

            if ($resturant_image == null) {
                return abort(400, 'There exists no image with this ID');
            };

            $jsonResponse = [];

            array_push($jsonResponse, [
                'id' => $resturant_image->id,
                'image' => $resturant_image->image,
                'resturant_id' => $resturant_image->resturant_id,
                'reports' => $resturant_image->reports,
                'is_bad' => $resturant_image->is_bad,
                ]);

            return $jsonResponse;
        }

   /** @OA\Delete(
        *     path="/api/resturantimages/delete/{id}",
        *     description="Delete a specific resturant image",
        *     tags={"Resturant Images"},
        *     @OA\Response(response="default", description="Delete a specific resturant images"),
        * @OA\Parameter(
        *         description="Id of resturant image",
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

            ResturantImage::find($request->id)->delete();

            return ['Resturant Image Deleted'];

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

            $resturant_image= ResturantImage::find($request->id);

            if ($resturant_image == null) {
                return abort(400, 'There exists no image with this ID');
            };

            $resturant_image->reports++;

            if($resturant_image->reports > 20) {
                $resturant_image->is_bad = True;
            }

            $resturant_image->update();

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
            *     tags={"Resturant Images"},
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

            $resturant_image = ResturantImage::find($request->id);

            if ($resturant_image == null) {
                return abort(400, 'There exists no image with this ID');
            };

            if(!$resturant_image->reports <= 0) {
                $resturant_image->reports--;
            } else {
                return abort(400, 'This resturant image does not have any reports');
            }

            if($resturant_image->reports <= 20) {
                $resturant_image->is_bad = False;
            };

            $resturant_image->update();

            $jsonResponse = [];

            array_push($jsonResponse, [
                'reports' => $resturant_image->reports,
                'is_bad' => $resturant_image->is_bad,
            ]);

            return $jsonResponse;
        }
}
