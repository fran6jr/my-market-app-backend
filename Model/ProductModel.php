<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class ProductModel extends Database
{
    public function getProducts($limit)
    {
        return $this->select("SELECT * FROM products LIMIT ?;", ["i", $limit]);
    }

    public function addProduct($product)
    {
        // $product = json_decode(file_get_contents("php://input"));
        
        $sku = $product["sku"];
        $name = $product["name"];
        $price = $product["price"];
        $size = $product["size"] ? $product["size"] : NULL;
        $weight = $product["weight"] ? $product["weight"] : NULL;
        $height = $product["height"] ? $product["height"] : NULL;
        $width = $product["width"] ? $product["width"] : NULL;
        $length = $product["length"] ? $product["length"] : NULL;

        return $this-createRemove("INSERT INTO products values(?, ?, ?, ?, ?, ?, ?, ?);", ["i", $sku, $name, $price, $size, $weight, $height, $width, $length]);
    }

    public function removeProducts($products = [])
    {
        $sku = array($products);
        $clause = implode(',', array_fill(0, count($sku), '?'));
        return $this-createRemove("DELETE FROM products WHERE sku in (" . $clause . ");");
    }

}