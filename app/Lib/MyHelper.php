<?php
namespace App\Lib;

use App\Http\Requests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Guzzle\Http\EntityBody;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Exception\ServerErrorResponseException;

use Illuminate\Support\Facades\URL;

class MyHelper
{

  public static function encodeImage($image){
    if ($image != null){
      $size   = $image->getSize();
      $encoded;
    if( $size < 90000000 ) {
      $encoded = base64_encode(fread(fopen($image, "r"), filesize($image)));
    }
    else {
      return false;
    }
    return $encoded;
    }
    else{
      $data = " ";
      return $data;
    }

  }

  public static function checkExtensionImageBase64($imgdata){
		 $f = finfo_open();
		 $imagetype = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);

		 if(empty($imagetype)) return '.jpg';
		 switch($imagetype)
		 {
				case 'image/bmp': return '.bmp';
				case 'image/cis-cod': return '.cod';
				case 'image/gif': return '.gif';
				case 'image/ief': return '.ief';
				case 'image/jpeg': return '.jpg';
				case 'image/pipeg': return '.jfif';
				case 'image/tiff': return '.tif';
				case 'image/x-cmu-raster': return '.ras';
				case 'image/x-cmx': return '.cmx';
				case 'image/x-icon': return '.ico';
				case 'image/x-portable-anymap': return '.pnm';
				case 'image/x-portable-bitmap': return '.pbm';
				case 'image/x-portable-graymap': return '.pgm';
				case 'image/x-portable-pixmap': return '.ppm';
				case 'image/x-rgb': return '.rgb';
				case 'image/x-xbitmap': return '.xbm';
				case 'image/x-xpixmap': return '.xpm';
				case 'image/x-xwindowdump': return '.xwd';
				case 'image/png': return '.png';
				case 'image/x-jps': return '.jps';
				case 'image/x-freehand': return '.fh';
				default: return false;
		 }
	}

	public static function uploadPhoto($foto, $path, $resize=1000, $name=null) {
			// kalo ada foto
			$decoded = base64_decode($foto);

			// cek extension
			$ext = MyHelper::checkExtensionImageBase64($decoded);

			// set picture name
			if($name != null)
				$pictName = $name.$ext;
			else
				$pictName = mt_rand(0, 1000).''.time().''.$ext;

			// path
			$upload = $path.$pictName;

			$img    = Image::make($decoded);

			$width  = $img->width();
			$height = $img->height();


			if($width > 1000){
					$img->resize(1000, null, function ($constraint) {
							$constraint->aspectRatio();
							$constraint->upsize();
					});
			}

			$img->resize($resize, null, function ($constraint) {
				$constraint->aspectRatio();
			});

			if ($img->save($upload)) {
					$result = [
						'status' => 'success',
						'path'  => $upload
					];
			}
			else {
				$result = [
					'status' => 'fail'
				];
			}

			return $result;
	}

  public static function convertDate($date){
    $date = explode('/', $date);
    $date = $date[2].'-'.$date[1].'-'.$date[0];
    $date = date('Y-m-d', strtotime($date));
    return $date;
  }

  public static function convertDate2($date){
    $date = explode('-', $date);
    $date = $date[2].'-'.$date[1].'-'.$date[0];
    $date = date('Y-m-d', strtotime($date));
    return $date;
  }

  public static function convertDateTime($date) {
    $date    = explode(' ', $date);
    $tanggal = explode("-", $date[0]);
    $tanggal = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
    $tanggal = $tanggal.' '.$date[1];
    $date    = date('Y-m-d H:i:s', strtotime($tanggal));
    return $date;
  }

  public static function postLogin($request){
    $api = env('APP_API_URL');
    $client = new Client(['headers' => ['User-Agent' => $request->server('HTTP_USER_AGENT')]]);

    try {
      $response = $client->request('POST',$api.'oauth/token', [
          'form_params' => [
              'grant_type'    => 'password',
              'client_id'     => env('PASSWORD_CREDENTIAL_ID'),
              'client_secret' => env('PASSWORD_CREDENTIAL_SECRET'),
              'username'      => $request->input('phone'),
              'password'      => $request->input('pin')
          ],
      ]);

      return json_decode($response->getBody(), true);
    }catch (\GuzzleHttp\Exception\RequestException $e) {
      try{
        if($e->getResponse()){
          $response = $e->getResponse()->getBody()->getContents();
          return json_decode($response, true);
        }
        else{
          return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
        }

      }
      catch(Exception $e){
        return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
      }
    }
  }

  public static function postLoginClient(){
    $api = env('APP_API_URL');
    $client = new Client;

    try {
      $response = $client->request('POST',$api.'oauth/token', [
          'form_params' => [
              'grant_type'    => 'client_credentials',
              'client_id'     => env('CLIENT_CREDENTIAL_ID'),
              'client_secret' => env('CLIENT_CREDENTIAL_SECRET'),
              'scope'      		=> '*'
          ],
      ]);

      return json_decode($response->getBody(), true);
    }catch (\GuzzleHttp\Exception\RequestException $e) {
      try{
        if($e->getResponse()){
          $response = $e->getResponse()->getBody()->getContents();
          return json_decode($response, true);
        }
        else{
          return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
        }

      }
      catch(Exception $e){
        return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
      }
    }
  }

  public static function get($url){
    $api = env('APP_API_URL');
    $client = new Client;

    $ses = session('access_token');

    $content = array(
      'headers' => [
        'Authorization' => $ses,
        'Accept'        => 'application/json',
        'Content-Type'  => 'application/json'
      ]
    );

    try {
      $response =  $client->request('GET',$api.'api/'.$url, $content);
      return json_decode($response->getBody(), true);
    }
    catch (\GuzzleHttp\Exception\RequestException $e) {
      try{

        if($e->getResponse()){
          $response = $e->getResponse()->getBody()->getContents();
          $error = json_decode($response, true);

          if(!$error) {
            return $e->getResponse()->getBody();
          }
          else {
           return $error;
          }
        }
        else return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];

      }
      catch(Exception $e){
        return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
      }
    }
  }

  public static function getNoAuth($url){
    $api = env('APP_API_URL');
    $client = new Client;

    $ses = session('access_token');

    $content = array(
      'headers' => [
        'Accept'        => 'application/json',
        'Content-Type'  => 'application/json'
      ]
    );

    try {


      $response =  $client->request('GET',$api.'api/'.$url, $content);

      $response =  $client->request('GET',$api.'api/'.$url, $content);

      $response =  $client->request('GET',$api.'api/'.$url, $content);

      return json_decode($response->getBody(), true);
    }
    catch (\GuzzleHttp\Exception\RequestException $e) {
      try{

        if($e->getResponse()){
          $response = $e->getResponse()->getBody()->getContents();
          $error = json_decode($response, true);

          if(!$error) {
            return $e->getResponse()->getBody();
          }
          else {
           return $error;
          }
        }
        else return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];

      }
      catch(Exception $e){
        return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
      }
    }
  }

  public static function post($url,$request){
    $api = env('APP_API_URL');
    $client = new Client;

    $ses = session('access_token');

    $post = $request->except('_token');

    $content = array(
      'headers' => [
        // 'Authorization' => session()->get('access_token'),
        'User-Agent'    => $request->header('User-Agent'),
        'Authorization' => $ses,
        'Accept'        => 'application/json',
        'Content-Type'  => 'application/json'
      ],
      'json' => (array) $post
    );

    try {
      $response = $client->post($api.'api/'.$url,$content);
      if(!is_array(json_decode($response->getBody(), true)));
		return json_decode($response->getBody(), true);
    }catch (\GuzzleHttp\Exception\RequestException $e) {
        try{
          if($e->getResponse()){
            $response = $e->getResponse()->getBody()->getContents();
            if(!is_array($response));
				return json_decode($response, true);
          }
          else  return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];

        }
        catch(Exception $e){
          return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
        }
    }
  }

  public static function postNoAuth($url,$post){
    $api = env('APP_API_URL');
    $client = new Client;

    $ses = session('access_token');

    $content = array(
      'headers' => [
        // 'Authorization' => session()->get('access_token'),
        'Accept'        => 'application/json',
        'Content-Type'  => 'application/json'
      ],
      'json' => (array) $post
    );

    try {
      $response = $client->post($api.'api/'.$url,$content);
      if(!is_array(json_decode($response->getBody(), true)));
		return json_decode($response->getBody(), true);
    }catch (\GuzzleHttp\Exception\RequestException $e) {
        try{
          if($e->getResponse()){
            $response = $e->getResponse()->getBody()->getContents();
            if(!is_array($response));
				return json_decode($response, true);
          }
          else  return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];

        }
        catch(Exception $e){
          return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
        }
    }
  }

  public static function postFile($url, $name_field, $path){
    $api = env('APP_API_URL');
    $client = new Client(

    );

    $ses = session('access_token');

    $content = array(
      'headers' => [
        'Authorization' => $ses,
        // 'Accept'        => 'application/json',
        // 'Content-Type'  => 'application/json'
      ],
      'multipart' => [
          [
              'name'     => $name_field,
              'contents' => fopen($path, 'r'),
              // 'filename' => $name
          ]
      ]
    );

    try {
      $response = $client->post($api.'api/'.$url,$content);
      if(!is_array(json_decode($response->getBody(), true)));
      return json_decode($response->getBody(), true);
    }catch (\GuzzleHttp\Exception\RequestException $e) {
        try{
          //print_r($e);
          if($e->getResponse()){
            $response = $e->getResponse()->getBody()->getContents();
            if(!is_array($response));
            return json_decode($response, true);
          }
          else  return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];

        }
        catch(Exception $e){
          return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
        }
    }
  }

  public static function postBiasa($url,$post){
    $api = env('APP_API_URL');
    $client = new Client;

    $content = array(
      'headers' => [
        'Content-Type'  => 'application/json'
      ],
      'json' => (array) $post
    );

    try {
      $response = $client->post($api.'api/'.$url,$content);
      // echo "a"; exit();
      if(!is_array(json_decode($response->getBody(), true)));
      return json_decode($response->getBody(), true);
    }catch (\GuzzleHttp\Exception\RequestException $e) {
        try{
          //print_r($e);
          if($e->getResponse()){
            $response = $e->getResponse()->getBody()->getContents();
            if(!is_array($response));
            return json_decode($response, true);
          }
          else  return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];

        }
        catch(Exception $e){
          return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
        }
    }
  }

  public static function put($url,$request){
    $api = env('APP_API_URL');
    $client = new Client;

    $ses = session('access_token');

    $post = $request->except('_token');

    $content = array(
      'headers' => [
        'Authorization' => session()->get('access_token'),
        'User-Agent'    => $request->header('User-Agent'),
        'Accept'        => 'application/json',
        'Content-Type'  => 'application/json'
      ],
      'json' => (array) $post
    );

    try {
      $response = $client->request('PUT', $api.'api/'.$url,$content);
      if(!is_array(json_decode($response->getBody(), true)));
		return json_decode($response->getBody(), true);
    }catch (\GuzzleHttp\Exception\RequestException $e) {
        try{
          if($e->getResponse()){
            $response = $e->getResponse()->getBody()->getContents();
            if(!is_array($response));
				return json_decode($response, true);
          }
          else  return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];

        }
        catch(Exception $e){
          return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
        }
    }
  }

  public static function putNoAuth($url,$post){
    $api = env('APP_API_URL');
    $client = new Client;

    $ses = session('access_token');

    $content = array(
      'headers' => [
        // 'Authorization' => session()->get('access_token'),
        'Accept'        => 'application/json',
        'Content-Type'  => 'application/json'
      ],
      'json' => (array) $post
    );

    try {
      $response = $client->request('PUT', $api.'api/'.$url,$content);
      if(!is_array(json_decode($response->getBody(), true)));
		return json_decode($response->getBody(), true);
    }catch (\GuzzleHttp\Exception\RequestException $e) {
        try{
          if($e->getResponse()){
            $response = $e->getResponse()->getBody()->getContents();
            if(!is_array($response));
				return json_decode($response, true);
          }
          else  return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];

        }
        catch(Exception $e){
          return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
        }
    }
  }

  public static function delete($url){
    $api = env('APP_API_URL');
    $client = new Client;

    $ses = session('access_token');

    $content = array(
      'headers' => [
        'Authorization' => $ses,
        'Accept'        => 'application/json',
        'Content-Type'  => 'application/json'
      ]
    );

    try {
      $response =  $client->request('DELETE', $api.'api/'.$url, $content);
      return json_decode($response->getBody(), true);
    }
    catch (\GuzzleHttp\Exception\RequestException $e) {
      try{

        if($e->getResponse()){
          $response = $e->getResponse()->getBody()->getContents();
          $error = json_decode($response, true);

          if(!$error) {
            return $e->getResponse()->getBody();
          }
          else {
           return $error;
          }
        }
        else return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];

      }
      catch(Exception $e){
        return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
      }
    }
  }

  public static function deleteNoAuth($url){
    $api = env('APP_API_URL');
    $client = new Client;

    $ses = session('access_token');

    $content = array(
      'headers' => [
        //'Authorization' => $ses,
        'Accept'        => 'application/json',
        'Content-Type'  => 'application/json'
      ]
    );

    try {
      $response =  $client->request('DELETE',$api.'api/'.$url);
      return json_decode($response->getBody(), true);
    }
    catch (\GuzzleHttp\Exception\RequestException $e) {
      try{

        if($e->getResponse()){
          $response = $e->getResponse()->getBody()->getContents();
          $error = json_decode($response, true);

          if(!$error) {
            return $e->getResponse()->getBody();
          }
          else {
           return $error;
          }
        }
        else return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];

      }
      catch(Exception $e){
        return ['status' => 'fail', 'messages' => [0 => 'Check your internet connection.']];
      }
    }
  }

  public static function custom_number_format($n) {

    //return number_format($n);

    // first strip any formatting;
    $n = (0+str_replace(",","",$n));

    // is this a number?
    if(!is_numeric($n)) return false;

    // now filter it;
    if($n>1000000000000) return round(($n/1000000000000),1).' T';
    else if($n>1000000000) return round(($n/1000000000),1).' B';
    else if($n>1000000) return round(($n/1000000),1).' M';
    else if($n>1000) return round(($n/1000),1).' K';

    return number_format($n);
  }

  public static function hasAccess($granted, $features){
    foreach($granted as $g){
      if(in_array($g, $features)) return true;
    }

    return false;
  }

  public static function getNotifications(){
    $data = MyHelper::post('notifications/list', ['limit' => 10, 'page' => 1]);

    return ($data['status'] == 'success') ? $data['result'] : null;
  }

      public static function  safe_b64encode($string) {
    $data = base64_encode($string);
    $data = str_replace(array('+','/','='),array('-','_',''),$data);
    return $data;
  }

  public static function  safe_b64decode($string)
  {
    $data = str_replace(array('-','_'),array('+','/'),$string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
      $data .= substr('====', $mod4);
    }
    return base64_decode($data);
  }

  public static function  passwordkey($id_user){
    $key = md5("esemestester".$id_user."644", true);
    return $key;
  }
  public static function  getkey() {
    $depan = MyHelper::createrandom(1);
    $belakang = MyHelper::createrandom(1);
    $skey = $depan . "9gjru84jb86c9l" . $belakang;
    return $skey;
  }

  public static function  parsekey($value) {
    $depan = substr($value, 0, 1);
    $belakang = substr($value, -1, 1);
    $skey = $depan . "9gjru84jb86c9l" . $belakang;
    return $skey;
  }

  public static function  createrandom($digit) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pin = '';

    while ($i < $digit) {
      $num = rand() % strlen($chars);
      $tmp = substr($chars, $num, 1);
      $pin = $pin . $tmp;
      $i++;
      // supaya char yg sudah tergenerate tidak akan dipakai lagi
      $chars = str_replace($tmp, "", $chars);
    }

    return $pin;
  }

  public static function  createrandomsku($digit) {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pin = '';

    while ($i < $digit) {
      $num = rand() % strlen($chars);
      $tmp = substr($chars, $num, 1);
      $pin = $pin . $tmp;
      $i++;
      // supaya char yg sudah tergenerate tidak akan dipakai lagi
      $chars = str_replace($tmp, "", $chars);
    }

    return $pin;
  }

  public static function throwError($e){
      $error = $e->getFile().' line '.$e->getLine();
      $error = explode('\\', $error);
      $error = end($error);
      return ['status' => 'failed with exception', 'exception' => get_class($e),'error' => $error ,'message' => $e->getMessage()];
  }
  public static function  encryptkhusus($value) {
    if(!$value){return false;}
    $skey = MyHelper::getkey();
    $depan = substr($skey, 0, 1);
    $belakang = substr($skey, -1, 1);
    $text = serialize($value);
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
    return trim($depan . MyHelper::safe_b64encode($crypttext) . $belakang);
  }

  public static function  encryptkhususpassword($value, $skey) {
    if(!$value){return false;}
    $text = serialize($value);
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
    return trim(MyHelper::safe_b64encode($crypttext));
  }

  public static function  decryptkhusus($value) {
    if(!$value){return false;}
    $skey = MyHelper::parsekey($value);
    $jumlah = strlen($value);
    $value = substr($value, 1, $jumlah-2);
    $crypttext = MyHelper::safe_b64decode($value);
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
    return unserialize(trim($decrypttext));
  }

  public static function  decryptkhususpassword($value, $skey)
  {
    if(!$value){return false;}
    $crypttext = MyHelper::safe_b64decode($value);
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
    return unserialize(trim($decrypttext));
  }

  public static function  createRandomPIN($digit, $mode = null)
  {
    if($mode != null)
    {
      if($mode == "angka")
      {
        $chars = "1234567890";
      }
      elseif($mode == "huruf")
      {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      }
      elseif($mode == "kecil")
      {
        $chars = "346789abcdefghjkmnpqrstuvwxy";
      }
    } else {
      $chars = "346789ABCDEFGHJKMNPQRSTUVWXY";
    }

    srand((double)microtime()*1000000);
    $i = 0;
    $pin = '';

    while ($i < $digit) {
      $num = rand() % strlen($chars);
      $tmp = substr($chars, $num, 1);
      $pin = $pin . $tmp;
      $i++;
    }
    return $pin;
  }

  public static function cekKode($kode)
  {
        // Bypass 1, 2, 5, i, I, o, O, z, Z
        $code = null;
        // $chr = ord($kode_promo[0]);
        $length = strlen($kode);
        for ($i = 0; $i < $length; $i++) {
            $keyCode = ord($kode[$i]);
            if ( !( ($keyCode >= 48 && $keyCode <= 57)
              ||($keyCode >= 65 && $keyCode <= 90)
              || ($keyCode >= 97 && $keyCode <= 122) )
              && $keyCode != 8 || $keyCode == 32
              || $keyCode == 73 || $keyCode == 79 || $keyCode == 90
              || $keyCode == 105 || $keyCode == 108
              || $keyCode == 111 || $keyCode == 122
              || $keyCode == 48 || $keyCode == 49 || $keyCode == 50
              || $keyCode == 53) {
                  continue;
            } else {
                $code = $code.$kode[$i];
            }
        }

        return $code;
  }
}

?>
