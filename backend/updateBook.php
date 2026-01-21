<?php
require_once "cors.php";
require_once "connection.php";


$data = json_decode(file_get_contents("php://input"), true);

$id = $data["id"];
$title = $data['title'];
$author = $data['author'];
$year = $data['year'];

$sql = "UPDATE books SET title = ?, author = ?, year = ? WHERE id = ? ";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ssis", $title, $author, $year, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Book added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add book"]);
}

$stmt->close();
$conn->close();
