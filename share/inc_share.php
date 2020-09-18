<?
	session_start();
	$devmode = true;
	if($devmode && empty($_REQUEST['action'])){
		die('404');
	}
	$fbappId = 'XXX';
	$twitterAccount = '@Zenergi';
	$campaignUrl = 'https://climatecrisis.zenergi.co.uk';
	// if($devmode){
	// 	$campaignUrl = 'https://climatecrisis.zenergi.co.uk';
	// }
	$clientUrl = 'https://climatecrisis.zenergi.co.uk';

	// share copy here
	$img = $campaignUrl.'/share/share.jpg';

	// Generic Game:
	// I’m playing @zenergi’s Climate Crisis game to see if I can save the planet and become carbon zero! Play for free here!

	$imgalt = 'Climate Crisis from Zenergi!';
	$siteName = 'Climate Crisis from Zenergi!';

	$title = 'Climate Crisis from Zenergi - Play for free and save the planet!';
	$description = 'Play Climate Crisis from Zenergi and see if you can save the planet and become carbon zero by 2050! Play for free!';

	$keywords = 'Zenergi, Climate Crisis, Online Game, Eco, Cimate, Green';
	$targetURL = $campaignUrl.'/?action=share';

	// ############# REMEMBER TO UPDATE THE URL IN THE SLIDE UP JS FILE TOO ####################//


	if(!empty($_REQUEST['action']) && strlen($_REQUEST['action']) > 0){
		$title = 'I’m playing @Zenergi’s Climate Crisis game to see if I can save the planet and become carbon zero! Play for free here!';
	}

	if(!empty($_REQUEST['player_score']) && strlen($_REQUEST['player_score']) > 0){
		$title = 'I just scored '.$_REQUEST['player_score'].' points in @Zenergi’s Climate Crisis game. Play it here (it’s free!) and see if you can beat my score!';
		//$description = 'description';
		$targetURL = $campaignUrl.'/?action=share&player_score='.$_REQUEST['player_score'];
	}

	if(!empty($_REQUEST['league_code']) && strlen($_REQUEST['league_code']) > 0){
		$title = 'Join my team on @Zenergi’s Climate Crisis game using code '.$_REQUEST['league_code'];
		//$description = 'description';
		$targetURL = $campaignUrl.'/?action=share&league_code='.$_REQUEST['league_code'];
	}

	if(!empty($_REQUEST['action']) && ($_REQUEST['action']=='share')){
		?>
		<script>
			window.location = '<?=$clientUrl?>';
		</script>
		<?
	}


?>
