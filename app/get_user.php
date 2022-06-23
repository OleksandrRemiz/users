<?php
require_once('db.php');
$id = $_POST["id"];
//$id = 1;
$response = new stdClass();

if($id){
	$user = R::findOne("users", "id = ?", [$id]);
	$response->code = true;
	$response->firstname = $user->firstname;
	$response->lastname = $user->lastname;
	$response->roleid = $user->role_id;
	$response->status = $user->status;

	$role = R::findOne("roles", "role_id = ?", [$id]);
	$response->rolename = $role->role;
}else{
	$error = new stdClass();
	$error->code = 400;
	$error->message = "Something went wrong.";
	$response->status = false;
	$response->error = $error;
}

echo json_encode($response);