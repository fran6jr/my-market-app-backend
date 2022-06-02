<?php
class Database
{
    protected $connection = null;
 
    public function __construct()
    {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);

            if ( mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");   
            }
        } catch (Exception $e) {
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
            $stmt = $this->executeStatement( $query , $params);
            $stmt->close();

            return true;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }

        return false;
    }
 
    private function executeStatement($query = "", $params = [])
    {
        try {
            $stmt = $this->connection->prepare( $query );
 
            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }

            if(count($params) > 0) {
                $refs = array();
                foreach($params as $key => $value) {
                    $refs[$key] = &$params[$key];
                }
                call_user_func_array(array($stmt, 'bind_param'), $refs);
            }

            $stmt->execute();
 
            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }
}