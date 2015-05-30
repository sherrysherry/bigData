<?php require dirname(__FILE__).'/MySQL/mysql.php';?>
<?php
	$result = sqlQuery("SELECT * FROM population");
		
    $data = array();

    for ($x = 0; $x < countNumOfRows($result); $x++) {
        $data[] = fetchArray($result);
    }

    echo json_encode($data);
?>