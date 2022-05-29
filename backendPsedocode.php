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
        echo "<table>";
            echo "<tr>";
                echo "<th>id</th>";
                echo "<th>first_name</th>";
                echo "<th>last_name</th>";
                echo "<th>email</th>";
            echo "</tr>";
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['first_name'] . "</td>";
                echo "<td>" . $row['last_name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        // Free result set
        mysqli_free_result($result);
    } else{
        echo "No records matching your query were found.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

# Delete Selected product entry from mysql database


# Add product to mysql database (Insert)


-->
