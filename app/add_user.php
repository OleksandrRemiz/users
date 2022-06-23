<?php
require_once('db.php');

$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$role_id = $_POST["role_id"];
$status = $_POST["status"];

$response = new stdClass();

if($firstname != "" && $lastname != ""){
	$user = R::dispense('users');
	$user["firstname"] = $firstname;
	$user["lastname"] = $lastname;
	$user["role_id"] = $role_id;
	$user["status"] = $status;
	R::store($user);

	$role = R::findOne("roles", "role_id = ?", [$role_id]);

	$response->status = true;
	$response->error = null;
	$response->user_id = $user->id;
	$response->role = $role->role;
}else{
	$error = new stdClass();
	$error->code = 100;
	$error->message = "One or both text fields are empty.";
	$response->status = false;
	$response->error = $error;
}

echo json_encode($response);
