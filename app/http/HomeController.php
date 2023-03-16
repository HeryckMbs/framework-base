<?php
namespace app\http;

class HomeController{
    public $t = 9;

    public function __construct(){
        $this->t = 10;
    }

}