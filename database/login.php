<?php session_start();

$_SESSION['fbID'] = "";
$_SESSION['gdID'] = "";
$_SESSION['dbID'] = "";

//POST - fbID -- facebook user ID

include("database.php");

if(!empty($_POST['fbID'])){
	$query = "SELECT COUNT(*), gdID, dbID FROM main WHERE fbID = ".$_POST['fbID'];
	$result = mysql_query($query) or die("Error: Query Error");
	$row = mysql_fetch_array($result);
	if($row[0] == 0){
		$query = "INSERT INTO main VALUES(".$_POST['fbID'].", '', '')";
		mysql_query($query) or die("Query Error");
		$sendArray = array("fbID"=>$_POST['fbID'], "gdID"=>"", "dbID"=>"");
		$_SESSION['fbID'] = $_POST['fbID'];
		$_SESSION['gdID'] = "";
		$_SESSION['dbID'] = "";
		echo json_encode($sendArray);
	}else{
		$sendArray = array("fbID"=>$_POST['fbID'], "gdID"=>$row['gdID'], "dbID"=>$row['dbID']);
		$_SESSION['fbID'] = $_POST['fbID'];
		$_SESSION['gdID'] = $row['gdID'];
		$_SESSION['dbID'] = $row['dbID'];
		echo json_encode($sendArray);
	}
	
}else{
	echo "Error: Wrong vars";
}



?>