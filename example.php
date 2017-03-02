<?php
require_once "HtmlCleaner.php";
$cleaner = new HtmlCleaner();
$habra = file_get_contents("letters/geektimes.html");
file_put_contents("result/geektimes.html",$cleaner->clean($habra));

$habra = file_get_contents("letters/redmine.html");
file_put_contents("result/redmine.html",$cleaner->clean($habra));


$habra = file_get_contents("letters/other_two.html");
file_put_contents("result/other_two.html",$cleaner->clean($habra));
//


$habra = file_get_contents("letters/blizzard.html");
file_put_contents("result/blizzard.html",$cleaner->clean($habra));

