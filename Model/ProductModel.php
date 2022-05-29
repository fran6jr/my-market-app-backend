<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class ProductModel extends Database
{
    public function getProducts() //($limit) // limit parameter makes sure we don't select all records at once. But, for now, we need all.
    {
        return $this->select("SELECT * FROM products"); /*ORDER BY user_id ASC LIMIT ?", ["i", $limit]);*/
    }
}