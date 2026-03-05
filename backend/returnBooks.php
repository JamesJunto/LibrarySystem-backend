<?php
session_start();
require_once 'cors.php';
require_once 'connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$bookId = $data['book_id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

if (!$bookId || !$userId) {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}
$conn->begin_transaction();

try {

    // DELETE FROM BORROWED
    $sql1 = "DELETE FROM borrowed WHERE user_id = ? AND book_id = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("ii", $userId, $bookId);
    $stmt1->execute();

    // UPDATE BOOKS
    $sql2 = "UPDATE books SET status = 0 WHERE book_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $bookId);
    $stmt2->execute();

    $conn->commit();

    echo json_encode([
        "status" => true,
        "message" => "Book returned successfully"
    ]);

} catch (Exception $e) {

    $conn->rollback();

    echo json_encode([
        "status" => "error",
        "message" => "Return failed"
    ]);
}

