<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class ProductModel extends Database
{
    public function getProducts($limit)
    {
        // echo("GET Products\n");
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

        $sql;
        $params = [];
        $error;
        
        $sqlctrl = $this->select("SELECT COUNT(*) FROM products WHERE sku=?;",["i", $sku]);
        
        if (!$price || !$name || !$sku)
            $error = "Missing required fields";
        else if ($weight && $size)
            $error = "Product cannot have both weight and size";
        else if ($height && $width && $length)
            $error = "Product dimensions are correct";
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
        else if($sqlctrl)
            $error = "Product sku exists";
    

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

    public function removeProducts($products = [])
    {
        $sku = array($products);
        $clause = implode(',', array_fill(0, count($sku), '?'));
        return $this->createRemove("DELETE FROM products WHERE sku in (" . $clause . ");");
    }
}