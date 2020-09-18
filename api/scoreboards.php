<?

$app->get('/scores', function () use ($app) { //CHRIS - FINISHED
	// ### get scores
	$data = $app->request();
	$league_id = ($data->get('league_id'))? $data->get('league_id') : 0;
	$anticache = ($data->get('anticache'))? $data->get('anticache') : 0;
	$app->getLog()->info('### GET /scores/'.$league_id);

	$sql = 'CALL spGetScores(:league_id)'; // sql string to call
	try {
	  $generateCache = false;

		$memcachekey = $app->APPMemcacheHash.'scores'.$league_id;
		if(($anticache>0)){
			deleteCache($memcachekey);
			$app->getLog()->info('### MEMCACHE: Delete Anticache: '. $memcachekey);
		}
		if(($response = getCache($memcachekey)) === false){
			$generateCache = true;
			$app->getLog()->info('### MEMCACHE: No cache found.');
		}

		if($generateCache){
			$app->getLog()->info('### MSQL: call mysql: '.$sql.' data:'.$league_id);
			$db = getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':league_id', intval($league_id),PDO::PARAM_INT);
			$stmt->execute();
			$scores = $stmt->fetchAll(PDO::FETCH_OBJ);
			// get live score form database write to json file

			$response = new \stdClass();
			//$response = $stmt->fetchAll(PDO::FETCH_OBJ);
			$response->code = '200';

			$response->scores = $scores;

			if($league_id==0){ //ONLY CACHE MAIN LEADERBOARD
				setCache($memcachekey,$response,172800);
				$app->getLog()->info(' Write MEM CACHE ');
			}
		} else {
			$app->getLog()->info('### MEMCACHE: Already Cached'.$generateCache);
		}

		$datasourcemsg =  $generateCache ? 'database' : 'memcache';
		$response->message = 'Source: '.$datasourcemsg;
		echo '{"response": '.koko_json_encode($response).'}';
		// success 200

	} catch(PDOException $e) {
		// mysql error 500
		$app->getLog()->info(' DB ERROR: '.$e->getMessage());
		$app->halt(500,'{"error":{"code":"500","message":"DB ERROR - '. $e->getMessage() .'"}}');
    }
});
?>
