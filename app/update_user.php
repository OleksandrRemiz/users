<?php
require_once('db.php');
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$id = $_POST["id"];
$role_id = $_POST["role_id"];
$status = $_POST["status"];

$response = new stdClass();

if($firstname != "" && $lastname != ""){
	R::exec('UPDATE `users` SET 
		`firstname` = :firstname,
		`lastname` = :lastname,
		`status` = :status,
		`role_id` = :role_id
		WHERE id = :id',
	[
	  'id' => $id,
	  'firstname' => $firstname,
	  'lastname' => $lastname,
	  'role_id' => $role_id,
	  'status' => $status
	]);

	$role = R::findOne("roles", "role_id = ?", [$role_id]);

	$response->status = true;
	$response->error = null;
	$response->role = $role->role;
}else{
	$error = new stdClass();
	$error->code = 100;
	$error->message = "One or both text fields are empty.";
	$response->status = false;
	$response->error = $error;
}

echo json_encode($response);