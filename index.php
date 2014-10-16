<?php

require_once("Controller/controller.php");

session_start();
$run = new \Controller\controller();

echo $run->run();
