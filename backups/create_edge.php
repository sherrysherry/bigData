<?php
	$_REQUEST['db'] = 'dataVisualization_sina';
	require dirname(__FILE__).'/MySQL/mysql.php';
	
	$senders = sqlQuery(" SELECT user_id FROM population ");
	for ($x = 0; $x < countNumOfRows($senders); $x++) {
	  $sender = fetchArray($senders);
		
		$connectionsNo = mt_rand(2,15);				
		$receivers = sqlQuery("
		SELECT user_id 
		FROM population
		WHERE user_id <> {$sender['user_id']}
		ORDER BY RAND()
		LIMIT {$connectionsNo}
		");
		
		
		
		for ($x = 0; $x < countNumOfRows($receivers); $x++) {
			$receiver = fetchArray($receivers);
			$user_id = $receiver['user_id'];
			$counts = mt_rand(1,500);		
			sqlQuery("INSERT INTO connections (sender,receiver,count) VALUES ({$sender['user_id']},{$receiver['user_id']},{$counts})");
		}		
	}
?>