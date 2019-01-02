<?php
/**
 * Created by PhpStorm.
 * User: marke
 * Date: 06/06/2018
 * Time: 23:06
 */

// Assign file paths to PHP constants
// __FILE__ returns the current path to this file
// dirname() returns the path to the parent directory
define("PRIVATE_PATH", dirname(__FILE__));//tells php -find the location of this file that you are currently on and go to it's directory
define("PROJECT_PATH", dirname(PRIVATE_PATH));//go two directories up from the current file you are on
define("PUBLIC_PATH", PROJECT_PATH . '/public');//when you are two directories up (in the root directory, go to public folder)
define("ADMIN_PATH", PROJECT_PATH . '/admin');//when you are two directories up (in the root directory, go to admin folder)
define("USER_PATH", PROJECT_PATH . '/user');//when you are two directories up (in the root directory, go to public folder)
define("SHARED_PATH", PROJECT_PATH . '/shared');//when you are two directories up (in the root root directory go to private folder)

//Define root of the website for url links

// Assign the root URL to a PHP constant
// * Do not need to include the domain
// * Use same document root as webserver

// * Can dynamically find everything in URL up to "/public"
$public_end = strpos($_SERVER['SCRIPT_NAME'], '/somethinglost') + 14; //finds first occurence of word somethinglost in url and adds 14
$doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end); //returns all symbols from the current url until the end of "somethinglost".
define("WWW_ROOT", $doc_root); //root is root  of the entire website

function url_for($script_path){
    if($script_path[0] != '/'){//if first symbol[0] of the script path array is not equal to /, then put /!!!
        $script_path = "/". $script_path;
    }
    return WWW_ROOT . $script_path;//always return WWW_ROOT when called out this function. very handy! Root is everyting up until the end of public/
}

//this is function for url encoding - for not to type encode each time
function u($string="") {
    return urlencode($string);
}

function raw_u($string=""){
    return rawurlencode($string);
}



//this function ensures no javascript inserted into html
function h($string=""){
    return htmlspecialchars($string);
}


function error_404(){
    header($_SERVER["SERVER_PROTOCOL"]."404 Not Found");
    exit();
}

function error_500(){
    header($_SERVER["SERVER_PROTOCOL"]."500 Internal Server Error");
    exit();
}

//function for redirect - it sets a header if we need to redirect something..

function redirect_to($location) {
    header("Location: " . $location);
    exit();

}

//function returns the result of evaluation and lets us find out
//if the server had Get or Post request. In other words this will
//return true if Post ..we can use this as a condition.

function is_post_request(){
    return $_SERVER['REQUEST_METHOD'] == 'POST';

}

function is_get_request(){
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

//prevent mysql injection:
function db_escape($connection, $string) {
    return mysqli_real_escape_string($connection, $string);
}

function user_lock($username, $permission){
    if((!isset($username))||($permission !== '0')){
        redirect_to('../public/login.php');
    }
}

function admin_lock($username, $permission){

    if(!isset($username)||($permission !== '1')){
        redirect_to('../public/login.php');
    }
}