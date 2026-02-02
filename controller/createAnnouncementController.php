<?php
session_start();
require_once "../model/database/database.php";
use DB\DBConn;

header('Content-Type: application/json');

$conn = new DBConn();
$connessioneOK = $conn->openConnection();

$id = $_SESSION["user"];



if ($connessioneOK && isset($_POST['title']) && isset($_POST['description'])) {

    
    $title = $_POST['title'];
    $description = $_POST['description'];

    $reservationResult = $conn->createAnnouncement($id, $title, $description);
    
    if($reservationResult) {
        echo json_encode(["status" => "success"]);
        header("Location: ../views/adminAnnouncements.php");
    } else {
        echo json_encode(["status" => "error", "message" => "Query fallita"]);
        header("Location: ../views/adminAnnouncements.php");
    }

} else {
    http_response_code(500);
    echo json_encode([
        "status" => "error", 
        "debug_post" => $_POST
    ]);
}
?>