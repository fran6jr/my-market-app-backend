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
        $sku = $product->sku;
        $name = $product->name;
        $price = $product->price;
        $size = $product->size ? $product->size : "";
        $weight = $product->weight ? $product->weight : "";
        $height = $product->height ? $product->height : "";
        $width = $product->width ? $product->width : "";
        $length = $product->length ? $product->length : "";


        $sql;
        $params = [];
        $error;

        $sql = "SELECT COUNT(*) FROM products WHERE sku = ?";
        $params = ["s", $sku];
        $result = $this->select($sql, $params);
        $count = $result[0]["COUNT(*)"];

        
        if (!$price || !$name || !$sku)
            $error = "Missing required fields";
        else if ($weight && $size)
            $error = "Product cannot have both weight and size";
        else if ($height && $width && !$length)
            $error = "Furniture must have length";
        else if ($height && !$width && $length)
            $error = "Furniture must have width";
        else if (!$height && $width && $length)
            $error = "Furniture must have height";
        else if ($weight && ($height || $width || $length))
            $error = "Product cannot have weight and dimensions";
        else if ($size && ($height || $width || $length))
            $error = "Product cannot have size and dimensions";
        else if (($size || $weight) && ($height || $width || $length))
            $error = "Product cannot have dimensions, size, and weight";
        else if (!$weight && !$size && !$height && !$width && !$length)
            $error = "Product must have weight, size, or dimensions";
        else if ($weight && $weight < 0)
            $error = "Product weight must be greater than 0";
        else if ($size && $size < 0)
            $error = "Product size must be greater than 0";
        else if ($height && $height < 0)
            $error = "Product height must be greater than 0";
        else if ($width && $width < 0)
            $error = "Product width must be greater than 0";
        else if ($length && $length < 0)
            $error = "Product length must be greater than 0";
        else if ($price < 0)
            $error = "Product price must be greater than 0";
        else if ($sku == "")
            $error = "Product SKU cannot be empty";
        else if ($name == "")
            $error = "Product name cannot be empty";
        else if($count > 0)
            $error = "Product exists";


        if ($error)
            throw new Exception($error);
        
        if ($weight) {
            $sql = "INSERT INTO products (sku, name, price, weight) VALUES (?, ?, ?, ?);";
            $params = ["ssdi", $sku, $name, $price, $weight];
        }
        else if ($size) {
            $sql = "INSERT INTO products (sku, name, price, size) VALUES (?, ?, ?, ?);";
            $params = ["ssdi", $sku, $name, $price, $size];
        }
        else {
            $sql = "INSERT INTO products (sku, name, price, height, width, length) VALUES (?, ?, ?, ?, ?, ?);";
            $params = ["ssdddd", $sku, $name, $price, $height, $width, $length];
        }

        return $this->createRemove($sql, $params);

    }

    public function removeProducts($skus = [])
    {
        $sql = "DELETE FROM products WHERE sku IN (";
        $params = [];
        $params[] = "s";
        foreach ($skus as $sku) {
            $sql .= "?,";
            $params[] = $sku;
        }
        
        $sql = substr($sql, 0, -1) . ");";

        return $this->createRemove($sql, $params);
    }
    
}