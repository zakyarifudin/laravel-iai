<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Lib\MyHelper;
use Session;
use Alert;

class UserController extends Controller
{
    public function viewlogin()
    {
        return view('login');
    }

    /*
        Login menggunakan $provider
        e.x google, github, facebook
    */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        $response = MyHelper::postNoAuth('jwt/login', $authUser);

        if(!isset($response['token'])){
            Alert::warning('Something wrong', 'Failed to Login')->persistent('Tutup')->autoclose(3000);
            return redirect('/login');
        }

        session([
            'access_token'  => 'Bearer '.$response['token']
        ]);

        $user = MyHelper::get('jwt/profile');

        session([
            'id_user'   => $user['id'],
            'username'  => $user['username'],
            'email'     => $user['email']
        ]);

        return redirect()->route('question');
    }

    public function findOrCreateUser($user, $provider)
    {
        $post = [
            'name'          => $user->name,
            'email'         => $user->email,
            'provider'      => $provider,
            'provider_id'   => $user->id
        ];

        $response = MyHelper::postNoAuth('user/find-or-create', $post);
        if (isset($response['status']) && $response['status'] == "success") {
            $request = [
                'email'     => $user->email,
                'password'  => $user->id,
            ];

            return $request;
        } else {
            Alert::success('', 'Successfully logout')->persistent('Tutup')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function postLogin(Request $request)
    {
        $request = $request->except('_token');
        $response = MyHelper::postNoAuth('jwt/login', $request);

        if(!isset($response['token'])){
            Alert::warning('Email or password invalid', 'Failed to Login')->persistent('Tutup')->autoclose(3000);
            return redirect('/login');
        }

        session([
            'access_token'  => 'Bearer '.$response['token']
        ]);

        $user = MyHelper::get('jwt/profile');

        session([
            'id_user'   => $user['id'],
            'username'  => $user['username'],
            'email'     => $user['email']
        ]);

        return redirect()->route('question');
    }

    public function viewRegister()
    {
        return view('register');
    }

    public function postRegister(Request $request)
    {
        $request = $request->except('_token');
        $response = MyHelper::postNoAuth('user', $request);

        if(isset($response['status']) && $response['status']=="success"){
            Alert::warning($response['message'], 'Success')->persistent('Tutup')->autoclose(3000);
            return redirect('/login');
        }
        else if(isset($response['status']) && $response['status']=="fail"){
            Alert::warning($response['message'], 'Gagal')->persistent('Tutup')->autoclose(3000);
            return redirect()->back();
        }
        else{
            $message = '';
            foreach($response as $each){
                $message .= $each['message'] .', ' ;
            }

            Alert::warning($message, 'Gagal')->persistent('Tutup');
            return redirect()->back();
        }
    }

    public function logout(){
        Session::flush();
        Alert::success('', 'Successfully logout')->persistent('Tutup')->autoclose(5000);
        return redirect('/login');
    }


}
