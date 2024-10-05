<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Kolkata');
class Database {
    private $host = 'localhost';
    // private $db_name = 'sonepatn_shop';
    // private $username = 'sonepatn_shop';
    // private $password = 'Modi@2024';
    private $db_name = 'ecom';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Constructor - Establishes database connection
    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db_name}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Function to execute a query
    public function executeQuery($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    // Function to insert data into the database
   public function insert($table, $data) {
    try {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $stmt = $this->conn->prepare($sql);

        $stmt->execute($data);
        return true; // Return true if insertion was successful
    } catch(PDOException $e) {
        // You can handle the error in different ways, such as logging it or throwing an exception
        error_log("Error inserting data: " . $e->getMessage());
        //echo $e->getMessage();
        return false; // Return false if insertion failed
    }
}

    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }

     public function prepare($sql) {
        return $this->conn->prepare($sql);
    }
    
    // Function to select data from the database
    public function select($query, $params = []) {
        try 
        {
            $stmt = $this->executeQuery($query, $params);
            return $stmt->fetchAll();
        }
        catch (PDOException $e) {

             error_log('Database select Error: ' . $e->getMessage());
             throw new Exception('An error occurred while fetching data.');
            // echo $query;
             return [];
        }
    }

    // Function to select data from the database
    public function select_single($query, $params = []) {
        try {
            $stmt = $this->executeQuery($query, $params);
            $result = $stmt->fetch(); // Fetch the result
            
            // Check if the result is false (no data found) and return null, otherwise return the result
            return $result ? $result : null;
        } catch (PDOException $e) {
            // Log the error and throw an exception
            error_log('Database select_single Error: ' . $e->getMessage());
            throw new Exception('An error occurred while fetching data.');
        } 
    }
    
    

    // Function to update data in the database
    public function update($table, $data, $where, $whereParams = []) 
    {
      try {  
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ', ');

        $query = "UPDATE $table SET $set WHERE $where";
        $params = array_merge($data, $whereParams); // Merge data and whereParams arrays

        $this->executeQuery($query, $params);
        return true;
     } 
     catch(PDOException $e) 
     {
         error_log("Error updating data: " . $e->getMessage());
         return false; // Return false if insertion failed
        
     }
}


    // Function to delete data from the database
    public function delete($table, $where, $params = []) 
    {
        try{
            $query = "DELETE FROM $table WHERE $where";
            $this->executeQuery($query, $params);
            return true;
         }
         catch(PDOException $e) 
         {
             error_log("Error updating data: " . $e->getMessage());
             return false; // Return false 
             
         }
    }
}

function GetProductThumbnail($product_id) {
    global $db;  // Assuming $db is an instance of the Database class
    $query = "SELECT * FROM product_images WHERE product_id = :product_id LIMIT 2";
    
    // Execute the query using the select_single method of the Database class
    try {
        $result = $db->select($query, ['product_id' => $product_id]);
        if (count($result) > 0) {
            return $result[0]['url']; // Return the first image's URL
        } else {
            return []; // No images found
        }
    } catch (Exception $e) {
        error_log('Error fetching product thumbnail: ' . $e->getMessage());
        return []; // Return empty array on error
    }
}


        function gotopage($url) {
            echo "<script language=\"javascript\">";
            echo "window.location = '".$url."'; \n";
            echo "</script>";
        }
        function dispMessage($msg,$url) {
            echo "<script language=\"javascript\">";
            echo "alert('".$msg."'); \n";
            echo "window.location = '".$url."'; \n";
            echo "</script>";
        }



  $arrTransType=array("Purchase","Sale","Sale Return","Trans In","Trans Out");
?>

