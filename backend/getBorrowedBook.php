<?php
session_start();
require_once "connection.php";
require_once "cors.php";


$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$sql = "SELECT borrow_id, borrowed.book_id, books.title, books.author, books.genre, books.year
FROM borrowed INNER JOIN books
ON borrowed.book_id = books.book_id
WHERE borrowed.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$books = [];

$result = $stmt->get_result();

while ($row = mysqli_fetch_assoc($result)) {
    $books[] = $row;
}

$borrowedBooks = $books;

echo json_encode($borrowedBooks);
