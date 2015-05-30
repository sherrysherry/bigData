<?php
	require dirname(__FILE__).'/MySQL/mysql.php';
	
	$GLOBALS['random'] = $_REQUEST['random'];	
	$user_id = $_REQUEST['id'];
	$order = 0;
	$seedCountSender = fetchArray(sqlQuery("SELECT count(*) as sender FROM connections WHERE sender = '{$user_id}' GROUP BY sender"));
	$seedCountReciver = fetchArray(sqlQuery("SELECT count(*) as receiver FROM connections WHERE receiver = '{$user_id}' GROUP BY receiver"));
	
	$GLOBALS['dataBase'] = 'topic_'.$_REQUEST['dataBase'];	
	$GLOBALS['maxOrder'] = 0;
	$GLOBALS['received'] = array();
	$GLOBALS['receive_order'] = array(); 
	$GLOBALS['stepValue'] = array(); 
	$GLOBALS['maxConnections'] = $seedCountSender['sender'] + $seedCountReciver['receiver'];
	
  array_push($GLOBALS['received'], $user_id);
  array_push($GLOBALS['receive_order'], array(
  	"id" => $user_id,
  	"order" => 0,
  	"prob" => 1,
  	"count" => $GLOBALS['maxConnections']
  ));		
  
	get_connected_node($user_id, $order);
	function get_connected_node($user_id, $order){
		$receivedNode = array();
		
		if( $order > $GLOBALS['maxOrder']){
			$GLOBALS['maxOrder'] = $order;
		}
		$order += 1;
				
		$result = sqlQuery("
		SELECT connections.receiver, {$GLOBALS['dataBase']}.prob 
		FROM {$GLOBALS['dataBase']} INNER JOIN connections 
		ON connections.receiver={$GLOBALS['dataBase']}.user_id AND connections.sender = '{$user_id}'");
				
    for ($x = 0; $x < countNumOfRows($result); $x++) {
	    $data = fetchArray($result);
			$inArray = null;
			foreach($GLOBALS['received'] as $key=>$i){
				if($data['receiver'] == $i){
					$inArray = 1;
					break;
				}
			}				
			
			if (!$inArray && ( (weightedRand(array((1 - $data['prob']) * 100, $data['prob'] * 100)) && $GLOBALS['random']) || (!$GLOBALS['random']))){			
				$seedCountSender = fetchArray(sqlQuery("SELECT count(*) as sender FROM connections WHERE sender = '{$data['receiver']}' GROUP BY sender"));
	$seedCountReciver = fetchArray(sqlQuery("SELECT count(*) as receiver FROM connections WHERE receiver = '{$data['receiver']}' GROUP BY receiver"));
				$seedCountData = $seedCountSender['sender'] + $seedCountReciver['receiver'];
				if($GLOBALS['maxConnections'] < $seedCountData){
					$GLOBALS['maxConnections'] = $seedCountData;
				}
			  array_push($GLOBALS['received'], "{$data['receiver']}");
		    array_push($GLOBALS['receive_order'], array(
		    	"id" => "{$data['receiver']}",
		    	"order" => "{$order}",
		    	"prob" => "{$data['prob']}",
		    	"count" => "{$seedCountData}"
		    ));
		    array_push($receivedNode, "{$data['receiver']}");							
			}					
    }
    
	  foreach($receivedNode as $receiver){
			get_connected_node($receiver, $order);
		}   
	}

	function weightedRand($weights, $weight_sum = 100){
	    $r = rand(1,$weight_sum);
	    $n = count($weights);
	    $i = 0;
	    while($r > 0 && $i < $n){
	        $r -= $weights[$i];
	        $i++;
	    }
	    return $i - 1;
	}
	
	function quick_sort($array){
		// find array size
		$length = count($array);
	
		// base case test, if array of length 0 then just return array to caller
		if($length <= 1){
			return $array;
		}
		else{
	
			// declare our two arrays to act as partitions
			$left = $right = $middle = array();

			// select an item to act as our pivot point, since list is unsorted first position is easiest
			$privot = $array[0]['order'];
	
			// loop and compare each item in the array to the pivot value, place item in appropriate partition
			for($i = 0; $i < count($array); $i++){
				if($array[$i]['order'] < $privot){
					$left[] = $array[$i];
				}
				else if($array[$i]['order'] > $privot){
					$right[] = $array[$i];
				}
				else if($array[$i]['order'] == $privot){
					$middle[] = $array[$i];
					if(!$GLOBALS['stepValue'][$privot]){
						$GLOBALS['stepValue'][$privot] = 1;
					}else{
						$GLOBALS['stepValue'][$privot] += 1;
					}
				}
			}

			// use recursion to now sort the left and right lists			
			return array_merge(quick_sort($left), $middle, quick_sort($right));
		}
	}
	
	$GLOBALS['stepValue'] = array_pad($GLOBALS['stepValue'], $GLOBALS['maxOrder'] + 1, 0);
	$GLOBALS['receive_order'] = quick_sort($GLOBALS['receive_order']);
	
	$data = array(
		'maxOrder' => $GLOBALS['maxOrder'],
		'receive_order' => $GLOBALS['receive_order'],
		'stepValue' => $GLOBALS['stepValue'],
		'maxCount' => $GLOBALS['maxConnections']
	);
	
	echo json_encode($data); 
	
?>