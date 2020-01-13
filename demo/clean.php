<?php
session_start();
if (!$_SESSION["valid_user"]) {
    Header("Location: login.php");
}
?>
<?php
include('../functions/database.php');
if ($_GET['d']) {

    $id = $_GET['d'];

if ($_SESSION["valid_user"]) {
    $sql = "DELETE from `links` where last_visit<DATE_SUB(curdate(), INTERVAL $id DAY)";
    $db->query($sql);
	echo "cleaned successfully";
	} else {
	echo "error";
	}
}else {
echo "error";
	}
	
?>