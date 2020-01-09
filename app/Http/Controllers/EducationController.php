<?php
namespace App\Http\Controllers;

use App\University;
use App\University_Departments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class EducationController extends Controller
{

    public function __construct()
    {
      //$this->middleware('verification');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */


    public function universities()
    {

        $result = University::all();
        return response()->json($result);


    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function departments($id)
    {

        $result = University::where("id",$id)->departments;
        return response()->json($result);

    }

//
}

?>
