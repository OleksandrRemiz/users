<?php
require_once('db.php');
$id = $_POST["id"];

$response = new stdClass();

if($id){
	R::exec('DELETE from `users` WHERE id = :id',
	[
	  'id' => $id
	]);
	$response->status = true;
	$response->error = null;
}else{
	$error = new stdClass();
	$error->code = 400;
	$error->message = "User not deleted.";
	$response->status = false;
	$response->error = $error;
}

echo json_encode($response);