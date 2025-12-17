<?php

require_once "builder.php";

use Builder\Builder;

$paginaHTML = file_get_contents("pages/service.html");

$builder = new Builder();
$page = $builder->build_home($paginaHTML);


echo $page;

?>