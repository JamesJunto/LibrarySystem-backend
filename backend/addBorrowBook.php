<?php
session_start();
require_once 'cors.php';
require_once 'connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$bookId = $data['book_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$bookId || !$user_id) {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}

$conn->begin_transaction();

try {

    // INSERT BOOKS ON BORROWED
    $sql1 = "INSERT INTO borrowed (user_id, book_id) VALUES (?, ?)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("ii", $user_id, $bookId);
    $stmt1->execute();

    // UPDATE books
    $sql2 = "UPDATE books SET status = 1 WHERE book_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $bookId);
    $stmt2->execute();



    $conn->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Book borrowed successfully"
    ]);

} catch (Exception $e) {

    $conn->rollback();

    echo json_encode([
        "status" => "error",
        "message" => "Borrow failed"
    ]);
}

$conn->close();
?>