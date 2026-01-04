<?php
require_once "builder.php";

use Builder\Builder;

$builder = new Builder();

if (!isset($_SESSION['user']) || $_SESSION['user'] === null) {
    header("Location: login.php");
    exit;
}


$paginaHTML = file_get_contents("pages/edit_account.html"); 
$page = $builder->build_account($paginaHTML);
echo $page;
?>