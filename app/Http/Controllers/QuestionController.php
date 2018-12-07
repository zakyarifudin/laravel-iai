<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Lib\MyHelper;

use Carbon\Carbon;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('islogin');
    }

    public function index(Request $request)
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
                    'created_at'    => Carbon::parse($value["created_at"])->diffForHumans(),
                    'comments'      => @count($value['comments'])
                ];
            }
        }

        // dd($data);

        $count_item = @count($data);
        $content = collect($data);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentResults = $content->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $results = new LengthAwarePaginator($currentResults, $content->count(), $perPage);

        // dd($results);
        return view('question.index', compact('results'));
    }

    public function store(Request $request)
    {
        $response = MyHelper::post('post', $request);
        if (isset($response['status']) && $response['status'] == "success") {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Successfully created the Question.',
            ];
            return redirect()->back()->with($chatter_alert);
        } else {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Failed to create the Question.',
            ];
            return redirect()->back()->with($chatter_alert);
        }

    }

    public function show($id)
    {
        $response = MyHelper::get('post/'.$id);
        $data = [];

        if (isset($response['status'])  && $response['status'] == "success" && $response['result'] != null) {
            $data = $response['result'];
        } else {
            return redirect()->route('question');
        }

        return view('question.show', compact("data"));

    }

    public function update(Request $request, $id)
    {
        $response = MyHelper::put('post/'.$id, $request);
        if (isset($response['status']) && $response['status'] == "success") {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Successfully updated the Question.',
            ];
            return redirect()->back()->with($chatter_alert);
        } else {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Failed to updat the Question.',
            ];
            return redirect()->back()->with($chatter_alert);
        }
    }

    public function destroy($id)
    {
        $response = MyHelper::delete('post/'.$id);
        if (isset($response['status']) && $response['status'] == "success") {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Successfully deleted the Question.',
            ];
            return redirect()->route('question')->with($chatter_alert);
        } else {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Failed to delete the Question.',
            ];
            return redirect()->back()->with($chatter_alert);
        }
    }
}
