<?php

http_response_code(500);

$basePath = dirname($_SERVER['SCRIPT_NAME']);

$html = file_get_contents((__DIR__ . '/pages/500.html'));

$page = str_replace("{{base_path}}", $basePath, $html);

echo $page;
?>