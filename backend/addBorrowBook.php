<?php
session_start();
require_once 'cors.php';
require_once 'connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$bookId = $data['book_id'] ?? null;

$user_id = $_SESSION['user_id'] ?? null;

$sql = "INSERT INTO borrowed (user_id, book_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $bookId);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Book added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add book"]);
}

