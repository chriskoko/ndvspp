<?
function koko_json_encode($response){

	//if (version_compare(phpversion(), '5.3.2', '>')) {
	//	$json = json_encode($response,JSON_NUMERIC_CHECK);
		// auto convert integers from string
//	} else {
		$json = json_encode($response);
		// php lower than 5.3.3 all ints cast as strings

	//}
	return $json;
}


function encryptToken($token){
    $data = $token.'||T||'.time();
    $key = '38f138468cdb4adba8a83335ecf5e0b6';
    $iv = pack("h*",hash_hmac('sha1', 'a'.$key.'b', $key).hash_hmac('sha1', 'z'.$key.'y', $key));
    $encrypted = base64_encode(\Slim\Http\Util::encrypt($data, $key, $iv));

    return  $encrypted;
}


function decryptToken($token){
    $data = $token;
    $key = '38f138468cdb4adba8a83335ecf5e0b6';
    $iv = pack("h*",hash_hmac('sha1', 'a'.$key.'b', $key).hash_hmac('sha1', 'z'.$key.'y', $key));
    $decrypted = \Slim\Http\Util::decrypt(base64_decode($token), $key, $iv);
    return  $decrypted;
}

function checktokenExpired($token,$ageMinutes){
	$tokenArray = decryptToken($token);
	$tokenArray = explode('||T||',$tokenArray);
	$now = time();
	$timestamp = intval($tokenArray[1]);
	$timediff  = $now - $timestamp;
	if (floor($timediff/60) > $ageMinutes) {
	   return false;
	} else {
		return true;
	}

}

function gettoken($token){
	$tokenArray = decryptToken($token);
	$tokenArray = explode('||T||',$tokenArray);
	return $tokenArray[0];
}






function koko_json_decode($json){
	$response = json_decode($json);
	$app = \Slim\Slim::getInstance();
	if($response == NULL){
		$app->getLog()->info('error json input malformed');
		$app->getLog()->info('###### json ###'.$json);
		$app->halt(400,'{"error":{"code":"400","message":"json input data malformed"}}');
		//invaild json throw bad request 400
	} else {
		if(strlen($fields)>1){
			//valid json error check fields name and types
			if(is_numeric(strpos($fields,','))===true){
				$array = explode(',',$fields);
			}else{
				$array[0] = $fields;
			}
			$errormessage = '';

			for($i=0;$i<count($array);$i++){

				$subarray = explode(':',$array[$i]);

				if(isset($response->$subarray[0])){
					if($subarray[1] == 'str'){
						// check value is string
						if(!is_string($response->$subarray[0])) $errormessage .= '\"'.$subarray[0].'\" not a String.\n';
					} else if($subarray[1] == 'int'){
						// check value is int
						if(!is_numeric($response->$subarray[0])) $errormessage .= '\"'.$subarray[0].'\" not a Integer.\n';
					}
				} else{
					// json either the wrong node name or extra nodes added
					$errormessage .= '\"'.$subarray[0].'\" field name not found or incorrect\n';
				}
			}
			if(strlen($errormessage)>1){
				$app->getLog()->info('error in json input data');
				$app->halt(400,'{"error":{"code":"400","message":"'.substr($errormessage,0, -2).'"}}');
				//json data invalid throw bad request 400
			} else{
				// json passed field checks return valid json
				return $response;
			}

		} else {
			// no field check return valid json
			return $response;
		}

	}
}


function guid(){
    if (function_exists('com_create_guid')){
        return str_replace('}','',str_replace('{','',str_replace('-','',com_create_guid())));
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = substr($charid, 0, 8).substr($charid, 8, 4).substr($charid,12, 4).substr($charid,16, 4).substr($charid,20,12);
        return $uuid;
    }
}
function full_url($s)
{
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
    return $protocol . '://' . $host . $port .$s['PHP_SELF'];//$s['REQUEST_URI'];
}

function sanitize_file_name($string, $force_lowercase = true, $anal = false) {
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "—", "-", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
}

function strchopper($str,$len){
	if(strlen($str) > $len){
		return substr($str,0,$len).'...';
	} else{
		return $str;
	}
}

function redirect($goto,$campaign){
	$seed = md5('k0k0-hh411*.$||');
	$hash = md5($seed.$goto);
	header('Location: http://www.kokodigital.co.uk/redirect.php?c='.$campaign.'&h='.$hash.'&goto='.$goto);
}

function funcTimeFormat($time){
	 $mins = floor($time/60000);
	 if(strlen($mins)<=1){
		 $mins= '0'.$mins;
	 }

	 $secs = floor(($time%60000)/1000);
	 if(strlen($secs)<=1){
		 $secs= '0'.$secs;
	 }

	 $microsecs = floor(($time%1000)/10);
	 if(strlen($microsecs)<=1){
		  $microsecs = '0'.$microsecs;
	 }

	   return $mins.':'. $secs.'.'.$microsecs;
}

function funcNameFormat($name){
    if(is_numeric(strpos($name,' '))===true){

    $array = explode(' ',$name);
    return $array[0].' '.substr($array[(count($array)-1)],0,1).'.';
    } else{
        return $name;
    }
}

?>
