<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class ProductModel extends Database
{
    public function get_Products($limit)
    {
        return $this->select("SELECT * FROM products LIMIT ?;", ["i", $limit]);
    }

    public function set_Product($product)
    {        
        $sku = $product->sku;
        $name = $product->name;
        $price = $product->price;
        $size = isset($product->size) ? $product->size : null;
        $weight = isset($product->weight) ? $product->weight : null;
        $height = isset($product->height) ? $product->height : null;
        $width = isset($product->width) ? $product->width : null;
        $length = isset($product->length) ? $product->length : null;


        $sql;
        $error;

        $sql = "SELECT COUNT(*) FROM products WHERE sku = ?";
        $params = ["s", $sku];
        $result = $this->select($sql, $params);
        $count = $result[0]["COUNT(*)"];
        
        if (preg_match('/[^A-Za-z0-9]/', $sku || $name || $size || $weight || $height || $width || $length))
            $error = 'Invalid data format';
        else if($count > 0)
            $error = "Product exists";
        else if (!$price || !$name || !$sku)
            $error = "Missing required fields";
        else if (isset($weight) && isset($size))
            $error = "Product cannot have both weight and size";
        else if (isset($weight) && (isset($height) || isset($width) || isset($length)))
            $error = "Product cannot have weight and dimensions";
        else if (isset($size) && (isset($height) || isset($width) || isset($length)))
            $error = "Product cannot have size and dimensions";
        else if ((isset($size) || isset($weight)) && (isset($height) || isset($width) || isset($length)))
            $error = "Product must be of a type";
        else if ((isset($height) || isset($width) || isset($length)) && !(isset($height) && isset($width) && isset($length)))
            $error = "Product must have all dimensions";
        else if (!isset($weight) && !isset($size) && !isset($height) && !isset($width) && !isset($length))
            $error = "Product must have weight, size, or dimensions";
        if (($price < 0) || (isset($weight) && $weight < 0) || (isset($size) && $size < 0) || (isset($height) && $height < 0) || (isset($width) && $width < 0) || (isset($length) && $length < 0))
            $error = "Invalid data - Input of type number must be positive";
        else if (!isset($sku))
            $error = "Product SKU cannot be empty";
        else if (!isset($name))
            $error = "Product name cannot be empty";


        if (isset($error))
            throw new Exception($error);
        
        if (isset($weight)) {
            $sql = "INSERT INTO products (sku, name, price, weight) VALUES (?, ?, ?, ?);";
            $params = ["ssdi", $sku, $name, $price, $weight];
        }
        else if (isset($size)) {
            $sql = "INSERT INTO products (sku, name, price, size) VALUES (?, ?, ?, ?);";
            $params = ["ssdi", $sku, $name, $price, $size];
        }
        else {
            $sql = "INSERT INTO products (sku, name, price, height, width, length) VALUES (?, ?, ?, ?, ?, ?);";
            $params = ["ssdddd", $sku, $name, $price, $height, $width, $length];
        }

        return $this->createRemove($sql, $params);

    }

    public function remove_Products($skus = [])
    { 
        if (count($skus) == 0)
            throw new Exception('No Products Selected');
        foreach ($skus as $sku) {
            if (preg_match('/[^A-Za-z0-9]/', $sku))
            throw new Exception('Invalid data format');
        }

        foreach ($skus as $sku) {
            $sql = "DELETE FROM products WHERE sku = ?";
            $params = ["s", $sku];
            $this->createRemove($sql, $params);
        }

        return true;
    }
    
}