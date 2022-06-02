<?php
class Database
{
    protected $connection = null;
 
    public function __construct()
    {
        try {
            // echo("Connecting to MySQL");
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
            // echo("After Connecting to MySQL");

            if ( mysqli_connect_errno()) {
                echo("Cannot Connect");
                throw new Exception("Could not connect to database.");   
            }
        } catch (Exception $e) {
            echo("Exception leading to Getmessage");
            throw new Exception($e->getMessage());   
        }           
    }
 
    public function select($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement( $query , $params);
            $product = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);               
            $stmt->close();
 
            return $product;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }

    public function createRemove($query = "", $params = []) {
        try {
            $stmt = $this->executeStatement( $query, $params );
            $stmt->close();
            
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
    }
 
    private function executeStatement($query = "", $params = [])
    {
        try {
            $stmt = $this->connection->prepare( $query );
 
            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }
 
            if( $params) {
                if ( count($params) == 2 )
                $stmt->bind_param($params[0], $params[1]); 

                if ( count($params) == 5 )
                $stmt->bind_param($params[0], $params[1], $params[2], $params[3], $params[4]);

                if ( count($params) == 7 )
                $stmt->bind_param($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7]);
            }
 
            $stmt->execute();
 
            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }   
    }
}