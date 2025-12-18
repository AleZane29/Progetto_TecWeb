<?php

require_once "builder.php";

use Builder\Builder;


$paginaHTML = file_get_contents("pages/account.html");

$builder = new Builder();
$page = $builder->build_account($paginaHTML);


echo $page;

?>