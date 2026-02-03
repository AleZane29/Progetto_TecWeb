<?php

require_once "builder.php";
use Builder\Builder;


if (!isset($_SESSION['user']) || $_SESSION['user'] === null) {
    header("Location: login.php");
    exit;
}


$builder = new Builder();
$page = $builder->build_personalReservations();

echo $page;
?>