<?php
	$_REQUEST['db'] = 'dataVisualization_sina';
	require dirname(__FILE__).'/MySQL/mysql.php';
	
	$result = sqlQuery(" SELECT * FROM population ");
	for ($x = 0; $x < countNumOfRows($result); $x++) {
	  $data = fetchArray($result);
	  $user_id = $data['user_id'];
		$pieces = explode(",", substr($data['geo'],31,-2));
		$latitude = $pieces[0];
		$longitude = $pieces[1];
		
		echo $latitude.'<br>';
		
		sqlQuery("UPDATE population SET latitude='{$latitude}', longitude='{$longitude}' WHERE user_id='{$user_id}'");
	}
?>