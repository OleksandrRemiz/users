<?php
require_once('db.php');
$action = $_POST["action"];
$users = $_POST["users"];

$response = new stdClass();

if($action && $users){
	if($action == "delete"){
		foreach($users as $id){
			R::exec("DELETE from `users` WHERE id = $id");
		}
	}else{
		if($action == "inactivate") $status = 0;
		if($action == "activate") $status = 1;
		foreach($users as $id){
			R::exec("UPDATE `users` SET `status` = $status WHERE id = $id");
		}
	}
	$response->status = true;
	$response->error = null;
}else{
	$error = new stdClass();
	$error->code = 400;
	$error->message = "Action not performed.";
	$response->status = false;
	$response->error = $error;
}

echo json_encode($response);