<?php


header("Access-Control-Allow-Origin: *");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    $head = array("https://products-store-eta.vercel.app/", "http://localhost:3000");
    
    if ((isset($_SERVER['HTTP_ORIGIN'])) && in_array($_SERVER['HTTP_ORIGIN'], $head)) { 
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    }
    header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Auth-Token");

    exit (200);
}

echo "testing";

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

$objFeedController->{$strMethodName}();

?>
