<?php include('../shared/header.php');?>

<?php
//session_unset();
//session_destroy();
//var_dump($_SESSION);
session_unset();
$result = session_destroy();

if($result){

    redirect_to('index.php?id=1');
}






