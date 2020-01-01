<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiToken;

class ApiTokenController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Get(
     *     path="/api/tokens/authorized",
     *     description="Create an authorized api token.",
     *     tags={"Tokens"},
     *     @OA\Response(response="default", description="Create a new authorized api token"),
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
    public function create_authorized_token(Request $request)
    {
        $level_2_tokens = ApiToken::where('level', '=', 2)->pluck('key')->toArray();

        if (!in_array($request->input('token'),  $level_2_tokens)) {
            return abort(401, 'Not authorized');
        }
        $api_token = new ApiToken;

        $api_token->level = 1;
        $api_token->key = md5(microtime().rand());

        $api_token->save();

        $jsonResponse = [];

        array_push($jsonResponse, [
            'api_token' => $api_token->key,
        ]);

        return $jsonResponse;
    }

    /**
     * @OA\Get(
     *     path="/api/tokens/superuser",
     *     description="Create a superuser api token.",
     *     tags={"Tokens"},
     *     @OA\Response(response="default", description="Create a new superuser api token"),
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
    public function create_superuser_token(Request $request)
    {
        $level_2_tokens = ApiToken::where('level', '=', 2)->pluck('key')->toArray();

        if (!in_array($request->input('token'),  $level_2_tokens)) {
            return abort(401, 'Not authorized');
        }

        $api_token = new ApiToken;

        $api_token->level = 2;
        $api_token->key = md5(microtime().rand());

        $api_token->save();

        $jsonResponse = [];

        array_push($jsonResponse, [
            'api_token' => $api_token->key,
        ]);

        return $jsonResponse;
    }


}
