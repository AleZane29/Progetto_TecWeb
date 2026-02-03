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

    $announcementResult = $conn->createAnnouncement($id, $title, $description);
    
    if($announcementResult) {
        header("Location: ../views/adminAnnouncements.php");
    } else {
        header("Location: ../views/500.php");
    }

} else {
    header("Location: ../views/500.php");
}
?>