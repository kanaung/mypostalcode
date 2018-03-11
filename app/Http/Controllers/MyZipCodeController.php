<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MyZipCodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @SWG\Info(title="Myanmar Postal Code API", version="0.1")
     */

    /**
     * @SWG\Get(
     *     path="/api/v1/postoffice/{postoffice}",
     *     @SWG\Response(
     *          response="200",
     *          description="Postal Codes",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/PostalCode")
     *          )
     *      )
     * )
     */

    public function postoffice($postoffice)
    {
        $codes = DB::table('postal_code')
            ->whereRaw('LOWER(post_office) like :postoffice', ['postoffice' => strtolower($postoffice) . '%'])
            ->get(['postal_code', 'post_office', 'township']);


        if ($codes !== null && !$codes->isEmpty()) {
            $return = response()->json($codes, 200);
        } else {
            $return = response()->json('Not Found', 404);
        }
        return $return;
    }

    public function township($township)
    {
        //DB::connection()->enableQueryLog();
        $codes = DB::table('postal_code')
            ->whereRaw('LOWER(township) = :township', ['township' => strtolower($township)])
            ->get(['postal_code', 'post_office', 'township']);
        //$queries    = DB::getQueryLog();
        //$last_query = end($queries);

//        echo 'Query<pre>';
//        print_r($last_query);

        if ($codes !== null && !$codes->isEmpty()) {
            $return = response()->json($codes, 200);
        } else {
            $return = response()->json('Not Found', 404);
        }
        return $return;
    }
}
