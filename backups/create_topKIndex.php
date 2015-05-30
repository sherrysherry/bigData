<?php
	//follower count x (sum of follow’s messages / sum of its own messages)
	$_REQUEST['db'] = 'dataVisualization_usaMessage';
	require dirname(__FILE__).'/MySQL/mysql.php';
	
	$nodes = sqlQuery("SELECT user_id FROM population");
	for ($x = 0; $x < countNumOfRows($nodes); $x++){
	  $node = fetchArray($nodes);
	  
	  $di = fetchArray(sqlQuery("SELECT count(*) as di FROM connections WHERE sender='{$node['user_id']}' GROUP BY sender"));
	  $follower_count = $di ?  $di['di'] : 0;
	  $probIndex = pow($follower_count,2);
	  sqlQuery("UPDATE population SET probIndex={$probIndex} WHERE user_id='{$node['user_id']}'");
	  
	  echo $node['user_id'].'  '.$probIndex.'<br>'; 
	  
	  
	  //echo $node['user_id'].'  '.$follower_count.'<br>';
	  
	  
	  //$idstr = fetchArray(sqlQuery("SELECT idstr FROM corresponing_id WHERE user_id={$node['user_id']}"));	  
	  //$sumOfSentMsg = fetchArray(sqlQuery("SELECT COUNT(*) as countNo FROM sina_weibo_keywords WHERE idstr={$idstr['idstr']} GROUP BY idstr"));
	  
	  /*
		Each of the follower has only one message
		
	  for($x = 0; $x < countNumOfRows($follows); $x++){
		  $follow = fetchArray($follows);
		  //SELECT `user_id`, COUNT(*) FROM sina_weibo_keywords GROUP BY `idstr` HAVING COUNT(*) > 1
		  $sumOfFollowsMsg = fetchArray(sqlQuery("SELECT count(*) as countNo FROM sina_weibo_keywords WHERE user_id={$node['user_id']} GROUP BY idstr"));
		  echo $follow['receiver'].' : ';
		  echo $sumOfFollowsMsg['countNo'].'<br>';
	  }
	  */
	}
?>