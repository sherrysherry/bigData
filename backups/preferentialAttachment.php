<?php
	$_REQUEST['db'] = 'DataVisualization';
	require dirname(__FILE__).'/MySQL/mysql.php';
		
	$graph = array('00001', '00002', '00003', '00004', '00005', '00006', '00007', '00008', '00009', '00010');	
	
	/*
	$graph = array();
	$results = sqlQuery("SELECT DISTINCT sender FROM connections_pa");
	for($x = 0; $x < countNumOfRows($results); $x++){
		$graph[] = fetchArray($results);
	}
	print_r($graph);
	*/
	
	preferentialAttachment($graph);
	function preferentialAttachment($graph){
		$m = count($graph);
		$nodes = sqlQuery("SELECT user_id FROM population ORDER BY user_id ASC LIMIT {$m} OFFSET {$m}");
		
		for ($x = 0; $x < countNumOfRows($nodes); $x++){
		  $node = fetchArray($nodes);
		  
		  for($x = 0; $x < $m; $x ++){	
			  $di = fetchArray(sqlQuery("SELECT count(*) as di FROM connections_pa WHERE sender={$graph[$x]} OR receiver={$graph[$x]} GROUP BY sender"));
			  $sumDi = fetchArray(sqlQuery("SELECT count(1) as di FROM connections_pa"));
			  $Pi = $di['di'] / $sumDi['di'];
			  
			  $buffer = weightedRand(array((1 - $Pi) * 100, $Pi * 100));
			  			  			  
			  if($buffer == 1){	
				 sqlQuery("INSERT INTO connections_pa (sender,receiver) VALUES ({$graph[$x]},{$node['user_id']})");				 
			  }
		  }
		  array_push($graph, $node['user_id']);	
		}
		preferentialAttachment($graph);
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
?>