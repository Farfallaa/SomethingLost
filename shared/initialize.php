<?php
/**
 * Created by PhpStorm.
 * User: marke
 * Date: 07/06/2018
 * Time: 06:28
 */
ob_start();//means that output buffering has been enabled (although it was enabled, but this is just in case)

require_once('functions.php');
require_once('database.php');
require_once('query_functions.php');


//db will be a variable that connects to database. We initialize it here.

$db = db_connect();


?>
