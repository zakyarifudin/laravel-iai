<?php

namespace App\Http\Controllers;

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

    public function postLogin(Request $request)
    {
        $request = $request->except('_token');
        $response = MyHelper::postNoAuth('jwt/login', $request);

        if(!isset($response['token'])){
            Alert::warning('Email atau password yang Anda masukkan salah', 'Gagal Login')->persistent('Tutup')->autoclose(3000);
            return redirect('/login');
        }

        session([
            'access_token'  => 'Bearer '.$response['token']
        ]);

        $user = MyHelper::get('jwt/profile');

        session([
            'username'  => $user['username'],
            'email'     => $user['email']
        ]);

        return 'login';
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
        Alert::success('', 'Anda berhasil keluar')->persistent('Tutup')->autoclose(5000);
        return redirect('/login');
    }


}
