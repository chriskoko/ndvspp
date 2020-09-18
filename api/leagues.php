<?

$app->get('/', function () use ($app) {
	echo '{"response": '.koko_json_encode('hello world').'}';
});


// $app->get('/memcachetest', function () use ($app){
// 	$app->getLog()->info('### GET /XMLDiff\Memorycachetest');
// 	$request = $app->request();
// 	$test = $request->get('test');
//
// 	$lcode = getCache('test');
// 	deleteCache('test');
// 	setCache('test',$test,120);
//
// 	// echo '{"response": '.koko_json_encode('test world').'}';
// 	echo '{"response": {"lcode":"'.$lcode.'"}}';
// });

$app->get('/leagues', function () use ($app){
	$app->getLog()->info('### GET /league');
	require '../config/hashids/HashGenerator.php';
	require '../config/hashids/Hashids.php';
	/* create the class object */
	$hashids = new Hashids\Hashids('ZZ2021$#!',4);

	$request = $app->request(); // get data send with request
	$player_guid = $request->get('guid');

	$sql = 'CALL spGetLeagues(:guid)';
	try {
		$app->getLog()->info('call mysql: '.$sql.' data:' .$request->getBody());
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':guid',strval($player_guid),PDO::PARAM_STR);
		$stmt->execute();

		$leaguesJson = $stmt->fetchAll(PDO::FETCH_OBJ);
		$stmt = null;
		$db = null;
		unset($stmt);
		unset($db);

		for($i=0;$i<count($leaguesJson );$i++){
			$leaguesJson[$i]->league_invite_code = $hashids->encode($leaguesJson[$i]->league_auto_id);
		}

		$response = new \stdClass();
		$response->code = '200';
		$response->leagues = $leaguesJson;

		$app->getLog()->info('league response: '.$response->code);
		echo '{"response": '.koko_json_encode($response).'}';

	} catch(PDOException $e) {
		// mysql error 500
		$app->getLog()->info(' DB ERROR: '.$e->getMessage());
		$app->halt(500,'{"error":{"code":"500","message":"DB ERROR - '. $e->getMessage() .'"}}');
	}

});


$app->post('/leagues', function () use ($app){
	$app->getLog()->info('### POST /leagues');
	require '../config/hashids/HashGenerator.php';
	require '../config/hashids/Hashids.php';
	/* create the class object */
	$hashids = new Hashids\Hashids('ZZ2021$#!',4);

	$request = $app->request(); // get data send with request
	$requestbody = koko_json_decode($request->getBody());

	$league_action = $requestbody->data->league_action;// EITHER 'create' 'join' 'leave'
	$league_name = $requestbody->data->league_name;
	$league_code = $requestbody->data->league_code;
	$player_guid = $requestbody->data->player_guid;

	$app->getLog()->info('### POST /league/'.$league_action);


switch ($league_action) {
 	case 'create':
		if(strlen($league_name)==0){
				$stopsubmission = true;
				$stopreason .= '[missing league name]';
		}
	break;
	case 'join':
	case 'leave':
		if(strlen($league_code)==0){
				$stopsubmission = true;
				$stopreason .= '[missing league code]';
		}
		$league_id = $hashids->decode($league_code)[0];
		$app->getLog()->info('### LEAGUE ID / CODE '.$league_id .'/'.$league_code);
	break;
}
if(strlen($player_guid)==0){
		$stopsubmission = true;
		$stopreason .= '[missing guid]';
}
if($stopsubmission){
		// stop code here
		$app->getLog()->info('424 stopped spam submission - '.$stopreason);
		$app->halt('424','{"response":{"code":"424","message":"'.$stopreason.'"}}');
}

	$sql = 'CALL spUpdateLeague(:guid,:name,:id,:action)';
	try {
		$app->getLog()->info('call mysql: '.$sql.' data:' .$request->getBody());
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':guid',strval($player_guid),PDO::PARAM_STR);
		$stmt->bindValue(':name',strval($league_name),PDO::PARAM_STR);
		$stmt->bindValue(':id',intval($league_id),PDO::PARAM_INT);
		$stmt->bindValue(':action',strval($league_action),PDO::PARAM_STR);
		$stmt->execute();
		$response = $stmt->fetchAll(PDO::FETCH_OBJ);
		$stmt = null;
		$db = null;
		unset($stmt);
		unset($db);

		$app->getLog()->info('mysql response: '.koko_json_encode($response));

		$app->getLog()->info('league response: '.$response[0]->code);

		//if($response[0]->code == '200'){
		if(strlen($response[0]->league_id) > 0){
	 			$league_code = $hashids->encode($response[0]->league_id);
				$response[0]->league_code = $league_code;
		}
		//}
		echo '{"response": '.koko_json_encode($response).'}';

	} catch(PDOException $e) {
		// mysql error 500
		$app->getLog()->info(' DB ERROR: '.$e->getMessage());
		$app->halt(500,'{"error":{"code":"500","message":"DB ERROR - '. $e->getMessage() .'"}}');
	}

});

?>
