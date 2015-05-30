<?php
	require dirname(__FILE__).'/../MySQL/mysql.php';	
	header("Content-Type:text/html; charset=utf-8");

	$result = sqlQuery("SELECT userID, text FROM sina_weibo_keywords");
			
  for ($x = 0; $x < countNumOfRows($result); $x++) {
      $data = fetchArray($result);
      
		  $so = scws_new();
			$so->set_charset('utf8');
			$so->set_dict('dict.utf8.xdb'); 
			$so->set_ignore(true);   
			$so->set_multi(true);  
		      
		  $so->send_text($data['text']);
		  $sentence = "";
			while ($tmp = $so->get_result()){
			  for($i = 0; $i < sizeof($tmp); $i++){
				  $sentence .= $tmp[$i]['word'].' ';
			  }
			}
			echo $data['userID'].':'.$sentence.'<br>';
			$userID = $data['userID'];
			//mysql_query("UPDATE sina_weibo_keywords SET keywords='{$sentence}' WHERE userID={$userID}");
			
			$so->close();
  }
?>