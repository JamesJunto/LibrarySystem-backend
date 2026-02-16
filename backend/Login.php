<?php
session_start();

require_once "cors.php";
require_once "connection.php";

$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'];
$password = $data['password'];

$sql = "SELECT * FROM user WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param("ss", $username, $password);

if( $stmt->execute() ){
    $result = $stmt->get_result();
    if( $result->num_rows > 0){
        $row = $result->fetch_assoc();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $row['user_id'];
        echo json_encode([ "success" => true, "message" =>"Login successful", "name" => $row['username']]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid email or password"]);
    }
}


