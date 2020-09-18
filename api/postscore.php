<?
		$app->post('/scores','authenticate', function () use ($app) {
		global $memcache;
		$app->getLog()->info('### POST /scores');

		$request = $app->request(); // get data send with request
	  $player = koko_json_decode($request->getBody());
		$player_ip = ($_SERVER['CF-Connecting-IP']) ? $_SERVER['CF-Connecting-IP'] : $_SERVER['REMOTE_ADDR'];
		$player_country = ($_SERVER["HTTP_CF_IPCOUNTRY"]) ? $_SERVER["HTTP_CF_IPCOUNTRY"] : '??';
		// get players ip address

		/// ### stop spamming below #######
		$stopsubmission = false;
		$stopreason = '';

		if(!$_SESSION['LAST_SUB_TIME']){
				$_SESSION['LAST_SUB_TIME'] = strtotime("now");
				// record time of last submission
		} else if(abs(strtotime("now")-intval($_SESSION['LAST_SUB_TIME'])) < intval($app->APPMinTimeSubmit) ){
				// time between submission to short stop score submission
				$stopsubmission = true;
				$stopreason .= '[time between submission to short '.abs(strtotime("now")-intval($_SESSION['LAST_SUB_TIME'])).']';
		}

		if (is_numeric(strpos($app->APPBannedIps,'|'.$playersIP.'|'))===true && is_numeric(strpos($app->APPBannedEmails ,'|'.$player->data->player_email.'|'))===true ){
				// if either ban list stop score submission
				$stopsubmission = true;
				$stopreason .= '[user on banned list]';
		}

		$player_guid = $player->data->player_guid;
		if(strlen($player_guid)==0){
				// if either ban list stop score submission
				$stopsubmission = true;
				$stopreason .= '[missing guid]';
		}

		$app->getLog()->info('app max score - '.intval($app->APPMaxScore));
		$app->getLog()->info('player score - '.intval($player->data->player_score));

		if(intval($player->data->player_score) > intval($app->APPMaxScore) || intval($player->data->player_score) < intval($app->APPMinScore) ){
				// limit min score allowed
				$stopsubmission = true;
				$stopreason .= '[score caught with min max code]';
		}
		if($stopsubmission){
				// stop code here
				$app->getLog()->info('424 stopped spam submission - '.$stopreason);
				$app->halt('424','{"error":{"code":"424","message":"error"}}');
		}
		#########################

	$sql = 'CALL spAddScore(:nickname,:cityname,:email,:score,:tnc,:guid)';

	try {
		$app->getLog()->info('call mysql: '.$sql.' data:' .$request->getBody());
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':nickname', strval(ucwords($player->data->player_nickname)),PDO::PARAM_STR);
		$stmt->bindValue(':cityname', strval(ucwords($player->data->player_cityname)),PDO::PARAM_STR);
		$stmt->bindValue(':email', strval($player->data->player_email),PDO::PARAM_STR);
		$stmt->bindValue(':score', intval($player->data->player_score),PDO::PARAM_INT);
		$stmt->bindValue(':tnc',intval($player->data->player_check_tnc),PDO::PARAM_INT);
		$stmt->bindValue(':guid', strval(ucwords($player->data->player_guid)),PDO::PARAM_STR);
		$stmt->execute();
		$response = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		$app->getLog()->info('mysql response: '.koko_json_encode($response));

		if($response[0]->code ==200){
			$_SESSION['LAST_SUB_TIME'] = strtotime("now");

			echo '{"response": '.koko_json_encode($response).'}';

			//check scoreboards cache
			//$myFile = $app->APPScoreboardFile;
			$league_id=0;
			$memcachekey = $app->APPMemcacheHash.'scores'.$league_id;
			if(($response = getCache($memcachekey)) !== false){
			//if(($response = getCache(str_replace('.json',$app->APPMemcacheHash,$myFile))) !== false) {

				if(intval($player->data->player_score) > (intval(end($response->scores)->player_score)) || count($response->scores) < intval($app->APPScoreboardDefaultLength)){
						//if score better than last in scoreboard then delete scoreboard so it gets recreated
						//$memcache->delete(str_replace('.json',$app->APPMemcacheHash,$myFile));
						$app->getLog()->info('SCORE: BETTER THAN CACHED');
						deleteCache($memcachekey);
				}
			}

			// success 200
		} else {
			$app->halt($response[0]->code,'{"error":'.koko_json_encode($response).'}');
			// coded error from sql
		}

	 } catch(PDOException $e) {
		// mysql error 500
		$app->getLog()->info(' DB ERROR: '.$e->getMessage());
		$app->halt(500,'{"error":{"code":"500","message":"DB ERROR - '. $e->getMessage() .'"}}');
    }

});

?>
