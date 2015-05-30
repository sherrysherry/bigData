<?php	
	require dirname(__FILE__).'/MySQL/mysql.php';	
	
	$topic = 'topic_'.$_REQUEST['dataBase'];
	$k = $_REQUEST['k'];
	$maxIndex = 0;
	$topKArray = array();
	$topKInform = array();
	
	$nodes = sqlQuery("SELECT user_id, probIndex FROM population");
	for ($x = 0; $x < countNumOfRows($nodes); $x++){
	  $node = fetchArray($nodes);
	  $prob = fetchArray(sqlQuery("SELECT prob FROM {$topic} WHERE user_id = '{$node['user_id']}'"));
	  $topKIndex = $prob['prob'] * $node['probIndex'];	  	  
	  	  
	  array_push($topKArray, array(
		  "user_id" => $node['user_id'],
		  "topKIndex" => $prob['prob'] * $node['probIndex']
	  ));
	  $topKArray = quick_sort($topKArray);
	  
	  if(count($topKArray) > $k){
		  $topKArray = array_slice($topKArray, 0, $k);
	  }	
	}	
		
	for($x = 0; $x < count($topKArray); $x++){
		$user_id = $topKArray[$x]['user_id'];
		$followers = fetchArray(sqlQuery("SELECT count(*) as amount FROM connections WHERE sender='{$user_id}' GROUP BY sender"));
		$prob = fetchArray(sqlQuery("SELECT prob FROM {$topic} WHERE user_id = '{$user_id}'"));
		$probIndex = fetchArray(sqlQuery("SELECT probIndex FROM population WHERE user_id = '{$user_id}'"));
		$topKIndex = $prob['prob'] * $probIndex['probIndex'];
				
		array_push($topKInform, array(
	  	"id" => "{$user_id}",
	  	"follower_count" => "{$followers['amount']}",
	  	"prob" => "{$prob['prob']}",
	  	"topKIndex" => "{$topKIndex}"
		));
	}
	$data = array(
		"maxK" => count($topKArray),
		"topKInform" => $topKInform
	);
	echo json_encode($data); 
	
	function quick_sort($array){
		$length = count($array);
	
		if($length <= 1){
			return $array;
		}
		else{
			$left = $right = $middle = array();
			$privot = $array[0]['topKIndex'];
			for($i = 0; $i < count($array); $i++){
				if($array[$i]['topKIndex'] > $privot){
					array_unshift($left, $array[$i]);
				}
				else if($array[$i]['topKIndex'] < $privot){
					array_unshift($right, $array[$i]);
				}
				else if($array[$i]['topKIndex'] == $privot){
					$middle[] = $array[$i];
				}
			}
			return array_merge(quick_sort($left), $middle, quick_sort($right));
		}
	}	
?>