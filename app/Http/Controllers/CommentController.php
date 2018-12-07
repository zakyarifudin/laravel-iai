<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Lib\MyHelper;

use Carbon\Carbon;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $response = MyHelper::post('post/'.$id.'/comment', $request);
        if (isset($response['status']) && $response['status'] == "success") {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Successfully created the Comment.',
            ];
            return redirect()->back()->with($chatter_alert);
        } else {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Failed to create the Comment.',
            ];
            return redirect()->back()->with($chatter_alert);
        }
    }

    public function update(Request $request, $id)
    {
        $response = MyHelper::put('post/comment/'.$id, $request);
        if (isset($response['status']) && $response['status'] == "success") {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Successfully updated the Comment.',
            ];
            return redirect()->back()->with($chatter_alert);
        } else {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Failed to update the Comment.',
            ];
            return redirect()->back()->with($chatter_alert);
        }
    }

    public function destroy($id)
    {
        $response = MyHelper::delete('post/comment/'.$id);
        if (isset($response['status']) && $response['status'] == "success") {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Successfully deleted the Comment.',
            ];
            return redirect()->back()->with($chatter_alert);
        } else {
            $chatter_alert = [
                'chatter_alert_type' => 'success',
                'chatter_alert'      => 'Failed to delete the Comment.',
            ];
            return redirect()->back()->with($chatter_alert);
        }
    }
}
