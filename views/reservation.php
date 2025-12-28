<?php

require_once "builder.php";

use Builder\Builder;


$builder = new Builder();
$page = $builder->build_reservation();


echo $page;

?>