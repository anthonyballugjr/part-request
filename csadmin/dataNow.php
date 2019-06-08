<?php
// header('Content-Type: application/json');
include_once "../config/database.php";
include_once "../classes/request.php";

$database = new Database();
$db = $database->getConnection();

$chart = new Request($db);
$x = $chart->chartToday();

print $x;
?>