<?php

class Result
{
    private $title = "";
    private $failed = 0;
    private $failColor = "rgb(196, 18, 44)";
    private $worked = 0;
    private $workColor = "rgb(66, 179, 66)";
    private $content = [];


    function __construct($title) {
        $this->title = $title;
    }

    function fails($content) {
        $this->content[] = "<span style='color: $this->failColor'>✗ $content</span>";
        $this->failed++;
    }

    function works($content) {
        $this->content[] = "<span style='color: $this->workColor'>✔ $content</span>";
        $this->worked++;
    }

    function printResult() {
        echo("<h1 style='color: wheat'>$this->title</h1>");
        echo("<h4><span style='color: $this->workColor'>Worked: $this->worked</span> <span style='color: wheat'>-</span> <span style='color: $this->failColor'>Failed: $this->failed</span></h4>");
        foreach ($this->content as $result) {
            echo("$result<br />");
        }
    }
}
