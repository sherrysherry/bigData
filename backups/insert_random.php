<?php
	$_REQUEST['db'] = 'dataVisualization_sina';
	require dirname(__FILE__).'/MySQL/mysql.php';
	
	$result = sqlQuery(" SELECT user_id FROM user_probability ");
	for ($x = 0; $x < countNumOfRows($result); $x++) {
	  $data = fetchArray($result);
	  $user_id = $data['user_id'];
		
		$prob_transportation = mt_rand (1, 999) / 1000;
		$prob_sports = mt_rand (1, 999) / 1000;
		$prob_military = mt_rand (1, 999) / 1000;
		$prob_medicine = mt_rand (1, 999) / 1000;
		$prob_astronomy = mt_rand (1, 999) / 1000;
		$prob_politics = mt_rand (1, 999) / 1000;
		$prob_education = mt_rand (1, 999) / 1000;
		$prob_culture = mt_rand (1, 999) / 1000;
		$prob_science = mt_rand (1, 999) / 1000;
		$prob_arts = mt_rand (1, 999) / 1000;
				
		sqlQuery("UPDATE user_probability 
		SET 
		prob_transportation='{$prob_transportation}', 
		prob_sports='{$prob_sports}',
		prob_military='{$prob_military}',
		prob_medicine='{$prob_medicine}',
		prob_astronomy='{$prob_astronomy}',
		prob_politics='{$prob_politics}',
		prob_education='{$prob_education}',
		prob_culture='{$prob_culture}',
		prob_science='{$prob_science}',
		prob_arts='{$prob_arts}'
		WHERE 
		user_id='{$user_id}'");
	}
?>