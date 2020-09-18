<?
function authenticate(\Slim\Route $route) {
	$app = \Slim\Slim::getInstance();
	$app->getLog()->info('### authenticate');

	$request = $app->request(); // get data send with request
	$data = koko_json_decode($request->getBody());
	$token = $_SESSION['token'];

	$app->getLog()->info('### token: '.$token);
	$app->getLog()->info('### auth session token: '.$_SESSION['token']);
	$app->getLog()->info('### auth session id: '.session_id());

	//if((strlen($token)==0) && ($bypassSecurity!='true')){
		// ### if token not set
	//	$app->getLog()->info('401 fail session data blank');
//	  $app->halt(401,'{"error":{"code":"401","message":"401 fail session data blank - Forbidden access."}}');
		// user failed Auth 401
	//} else {
	// ### check token
	//$checktoken = md5(substr($token,8,15).substr($token,3,7).substr($token,5,3));
	if(isset($data->data->player_score)) {
	        $app->getLog()->info('### player score ' . $data->data->player_score);

					//make shift secirity bolt-on - to combine playerscore with
	        $checktoken = (substr($data->data->token, 8, 15) .
											substr($data->data->player_score,0,1) .
											substr($data->data->token, 3, 7) .
											substr($data->data->player_score,strlen($data->data->player_score)-1,strlen($data->data->player_score)) .
											substr($data->data->token, 5, 3));

	        $app->getLog()->info('### token encrypted should be' . $checktoken);
					$app->getLog()->info('### secure token =' . $data->data->secure_token);
	    }
	    if(($checktoken == $data->data->secure_token)){
	        $app->getLog()->info('Auth success');
	        return true;
	        // Success Auth
	    }else{

	    //	if(strlen($app->getCookie('token'))){
	    //	    $app->deleteCookie('token');
	    //	   $app->deleteCookie('_lst');
	    //	}
	        $app->getLog()->info('401 token not matching ');
	        $app->halt(401,'{"error":{"code":"401","message":"401 token not matching - Forbidden access."}}');
	        //User failed Auth
	    }
	}
	// $stopsubmission = true;
	// $stopreason .= '[score caught with min max code]';
	// if($stopsubmission){
	// 	// stop code here
	// 	$app->getLog()->info('401 token error - '.$stopreason);
	// 	$app->halt('401','{"error":{"code":"401","message":"token error"}}');
	// }
	return false;
//};
?>
