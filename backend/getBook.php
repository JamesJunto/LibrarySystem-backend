<?php
require_once "cors.php";
require_once "connection.php";

$sql = "SELECT * FROM books";
$result = mysqli_query($conn, $sql);

$books = [];

while ($row = mysqli_fetch_assoc($result)) {
    $books[] = $row;
}

$json_convert = json_encode($books);

echo $json_convert;

$conn->close();

