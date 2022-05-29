<?php

# Select and Load products from mysql database

$pDB = "productDB";

$con = mysql_connect("localhost","root","");

if (!$con) {
  die("Could not connect: " . mysqli_connect_error())
}

mysql_select_db($pDB, $con); # could be removed

$query = SELECT * FROM products;

while($products = mysqli_query($con, $query)){
    if(mysqli_num_rows($products) > 0){
        // get products
        mysqli_free_result($products);
    } else{
        echo "No records matching your query were found.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

# Delete Selected product entry from mysql database


# Add product to mysql database (Insert) using post



-->
