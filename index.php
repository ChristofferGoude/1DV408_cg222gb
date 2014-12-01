<?php

require_once("Controller/controller.php");

// Index starts by initiating the controller.
session_start();
$run = new \Controller\controller();

echo $run->run();
