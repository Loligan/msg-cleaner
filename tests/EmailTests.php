<?php
//require_once "../vendor/autoload.php";
require_once "../HtmlCleaner.php";

class EmailTests extends \PHPUnit\Framework\TestCase
{
    public function removeJSInHead()
    {
        $letter = file_get_contents("letters/removeJSInHead.html");
        $cleaner = new Cleaner();
        $result = $cleaner->clean($letter);
        if(stristr($result,"<a>")){
            throw new Exception("Tags <a> not be closed");
        }
        if(stristr($result,"<div>")){
            throw new Exception("Tags <a> not be closed");
        }

    }

    public function removeJSInBody()
    {
        $letter = file_get_contents("letters/removeJSInBody.html");
        $cleaner = new Cleaner();
        $cleaner->clean($letter);

    }

    public function closeTags(){
        $letter = file_get_contents("letters/closeTags.html");
        $cleaner = new Cleaner();
        $cleaner->clean($letter);
    }

    public function addCloseTags(){
        $letter = file_get_contents("letters/addCloseTags.html");
        $cleaner = new Cleaner();
        $cleaner->clean($letter);
    }

}