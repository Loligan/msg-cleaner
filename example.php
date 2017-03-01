<?php
require_once "Cleaner.php";
$cleaner = new Cleaner();
$habra = file_get_contents("letters/habrahabr.html");
file_put_contents("result/habrahabr.html",$cleaner->clean($habra));

