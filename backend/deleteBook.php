    <?php
    require_once "cors.php";
    require_once "connection.php";

    $data = json_decode(file_get_contents("php://input"),true);
    
    $sql = "DELETE from books WHERE id = ?";
    $stmt = $conn->prepare($sql);

    $bookId = $data['id'];
    $stmt->bind_param("i", $bookId);

    $stmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "Book deleted",
        "affected_rows" => $stmt->affected_rows
    ]);