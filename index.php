<?php

header("Access-Control-Allow-Origin: *");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Auth-Token");
    exit(200);
}


require __DIR__ . "/inc/bootstrap.php";

 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
 
if ((isset($uri[2]) && $uri[2] != 'product') || !isset($uri[3])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
 
require PROJECT_ROOT_PATH . "/Controller/Api/ProductController.php";
 
$objFeedController = new ProductController();
$strMethodName = $uri[3] . 'Action';
// echo("this is live \n");
$objFeedController->{$strMethodName}();
echo("this is the end ");

// list http://localhost/index.php/product/list
// add http://localhost/index.php/product/add
// delete http://localhost/index.php/product/delete
?>
