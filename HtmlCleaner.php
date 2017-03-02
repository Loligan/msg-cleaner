<?php
require_once "vendor/autoload.php";

class HtmlCleaner
{
    private $tidy;
    private $configTidy;

    public function __construct()
    {

        $this->configTidy = array(
            'indent' => true,
            'output-html' => true,
            'input-encoding' => 'utf8'
        );
        $this->tidy = new tidy();
    }

    private function tidyClean($letter)
    {
        $this->tidy->parseString($letter, $this->configTidy, 'utf8');
        $this->tidy->cleanRepair();

        $result = tidy_get_output($this->tidy);
        return $result;
    }


    private function cleanOtherScripts($letter)
    {
        $letter = explode('<code>', $letter);
        $codes = array();
        if (count($letter) > 1) {
            foreach ($letter as $idx => $val) {
                $val = explode('</code>', $val);
                if (count($val) > 1) {
                    $uid = md5(uniqid(mt_rand(), true));
                    $codes[$uid] = htmlentities(array_shift($val), ENT_QUOTES, 'UTF-8');
                    $letter[$idx] = "##$uid##" . implode('', $val);
                }
            }
        }
        $letter = implode('', $letter);
        while (stripos($letter, '<script') !== false) {
            $letter = str_ireplace('<script', '&lt;script', $letter);
        }
        $rptjob = function (&$str, $regexp) {
            while (preg_match($regexp, $str, $matches)) {
                $str = str_ireplace($matches[0], htmlentities($matches[0], ENT_QUOTES, 'UTF-8'), $str);
            }
        };
        $rptjob($letter, '/href[\s\n\t]*=[\s\n\t]*[\"\'][\s\n\t]*(javascript:|data:)/i'); //href = "javascript:
        $rptjob($letter, '/style[\s\n\t]*=[\s\n\t]*[\"][^\"]*expression/i'); //style = "...expression
        $rptjob($letter, '/style[\s\n\t]*=[\s\n\t]*[\'][^\']*expression/i'); //style = '...expression
        $rptjob($letter, '/style[\s\n\t]*=[\s\n\t]*[\"][^\"]*behavior/i'); //style = "...behavior
        $rptjob($letter, '/style[\s\n\t]*=[\s\n\t]*[\'][^\']*behavior/i'); //style = '...behavior
        $rptjob($letter, '/on\w+[\s\n\t]*=[\s\n\t]*[\"\']/i'); //onasd = "
        return $letter;
    }


    private function cleanScriptTags($header)
    {
        $result = preg_replace("'<script[^>]*?>.*?</script>'si", "", $header);
        return $result;
    }

    public function clean($letter)
    {
        $letter = $this->tidyClean($letter);
        $letter = $this->cleanScriptTags($letter);
        $result = $this->cleanOtherScripts($letter);
        $result = $this->tidyClean($result);
        return $result;
    }
}


