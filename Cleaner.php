<?php
require_once "vendor/autoload.php";

class Cleaner {
    private $tidy;
    private $configTidy;
    private $purifilter;

    public function __construct()
    {

        $config = HTMLPurifier_Config::createDefault();

        $this->purifilter = new HTMLPurifier($config);
        $this->configTidy= array(
            'clean' => true,
            'output-html' => true
        );
        $this->tidy = new tidy();

    }

    private function tidyClean($letter){
        $this->tidy->parseString($letter, $this->configTidy, 'utf8');
        $this->tidy->cleanRepair();
        $result = tidy_get_output($this->tidy);
        return $result;

    }

    private function purifilterClean($letter){


        $result = $this->purifilter->purify($letter);
        return $result;
    }


    private function cleanScriptTags($header){
        $result = preg_replace("'<script[^>]*?>.*?</script>'si","",$header);
        return $result;
    }

    public function clean($letter){
        $header = null;
        $body = null;

        $this->tidyClean($letter);
        preg_match ("/(<head[^>]*?>.*<.head>)/si",  $letter, $out);
        if($out){
            $header= $out[0];
            $header = $this->cleanScriptTags($header);
        }
        $body = $this->purifilterClean($letter);
        $result = "<html>".$header."<body>".$body."</body><html>";
        return $result;
    }
}


