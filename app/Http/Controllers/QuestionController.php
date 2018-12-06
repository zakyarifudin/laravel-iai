<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Lib\MyHelper;

use Carbon\Carbon;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('islogin');
    }

    public function index()
    {
        $response = MyHelper::get('post');
        $data = [];

        if (isset($response['status'])  && $response['status'] == "success") {
            foreach ($response['result'] as $key => $value) {
                // Trim description
                $desc = $value['description'];
                $string = strip_tags($desc);
                if (strlen($string) > 200) {

                    // truncate string
                    $stringCut = substr($string, 0, 200);
                    $endPoint = strrpos($stringCut, ' ');

                    //if the string doesn't contain any space then it will cut without word basis.
                    $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                    $string .= '... ';
                }

                // Get first char in username
                $char = substr($value['user']['username'], 0, 1);

                $data[$key] = [
                    'id'            => $value['id'],
                    'user_id'       => $value['user_id'],
                    'username'      => $value['user']['username'],
                    'char'          => $char,
                    'email'         => $value['user']['email'],
                    'title'         => $value['title'],
                    'description'   => $string,
                    'created_at'    => Carbon::parse($value['created_at'])->diffForHumans(),
                    'comments'      => @count($value['comments'])
                ];
            }
        }

        // dd($data);
        return view('question', compact('data'));
    }
}
