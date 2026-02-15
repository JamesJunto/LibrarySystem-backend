<?php
 session_start();

 require_once 'cors.php';

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['loggedIn' => false]);
        exit;
    }
    echo json_encode(['loggedIn' => true]);