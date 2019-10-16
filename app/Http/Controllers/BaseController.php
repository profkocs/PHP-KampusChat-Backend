<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result,$code = 200 )
    {
        $response = [
            'result'  => $result,
        ];


        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($result , $errorMessages = [], $code = 404)
    {

      $response = [
          'result'    => $result,
          'message'   => $errorMessages
      ];


        return response()->json($response, $code);
    }
}
