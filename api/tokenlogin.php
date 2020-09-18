<?
$app->get('/token', function () use ($app) {
	// ### get token and extra game info
	// data ?app_version=1.0"
	$data = $app->request(); // get data send with request
	$app->getLog()->info('### GET /token');

	try {
		$token = guid();
		$_SESSION['token'] = $token;

		$app->getLog()->info('### get token session token: '.$_SESSION['token']);
		$app->getLog()->info('### get token session id: '.session_id());

		$response = new stdClass();
		$response->code = '200';
		$response->message = 'New token/guid created. Please only request once and save locally as cookie.';
		$response->token = $token;
		$response->guid = guid();

		$tokentest = $_SESSION['token'];
  	$app->getLog()->info('### GET /token ----- '.$tokentest);
		$app->getLog()->info('response: '.koko_json_encode($response));

		echo '{"response": '.koko_json_encode($response).'}';
		// success 200
	 } catch(Exception $e) {
		// error 500
		$app->getLog()->info(' DB ERROR: '.$e->getMessage());
		$app->halt(500,'{"error":{"code":"500","message":"'. $e->getMessage() .'"}}');
    }
});



// $app->get('/settesttoken', function () use ($app) {
// 		$_SESSION['testtoken'] = '23123123123123123';
// 		echo '{"response": '.koko_json_encode($_SESSION['testtoken']).'}';
// });
//
// $app->get('/gettesttoken', function () use ($app) {
// echo '{"response": '.koko_json_encode($_SESSION['testtoken']).'}';
// });
//
// $app->get('/getrealtoken', function () use ($app) {
// echo '{"response": '.koko_json_encode($_SESSION['token']).'}';
// });

?>
