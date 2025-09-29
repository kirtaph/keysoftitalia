<?php
/**
 * Key Soft Italia - Database Class
 * Gestione connessione e query database con PDO
 */

class Database {
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $charset = 'utf8mb4';
    private $pdo;
    private $error;
    private $stmt;
    
    /**
     * Constructor - Initialize database connection
     */
    public function __construct() {
        $this->host = DB_HOST;
        $this->dbname = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        
        // Set DSN
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        
        // Set options
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        // Create PDO instance
        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            if (DEBUG_MODE) {
                die("Database connection failed: " . $this->error);
            } else {
                die("Si è verificato un errore di connessione al database.");
            }
        }
    }
    
    /**
     * Prepare statement with query
     */
    public function query($sql) {
        $this->stmt = $this->pdo->prepare($sql);
        return $this;
    }
    
    /**
     * Bind values
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }
    
    /**
     * Execute the prepared statement
     */
    public function execute() {
        try {
            return $this->stmt->execute();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            if (DEBUG_MODE) {
                die("Query execution failed: " . $this->error);
            }
            return false;
        }
    }
    
    /**
     * Get result set as array of objects
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }
    
    /**
     * Get single record as object
     */
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }
    
    /**
     * Get row count
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    /**
     * Get last insert ID
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Insert data into table
     */
    public function insert($table, $data) {
        $fields = array_keys($data);
        $values = array_map(function($field) {
            return ":{$field}";
        }, $fields);
        
        $sql = "INSERT INTO {$table} (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $values) . ")";
        
        $this->query($sql);
        
        foreach($data as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        if ($this->execute()) {
            return $this->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update data in table
     */
    public function update($table, $data, $where, $whereData = []) {
        $fields = array_map(function($field) {
            return "{$field} = :{$field}";
        }, array_keys($data));
        
        $sql = "UPDATE {$table} SET " . implode(', ', $fields) . " WHERE {$where}";
        
        $this->query($sql);
        
        // Bind update data
        foreach($data as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        // Bind where data
        foreach($whereData as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        return $this->execute();
    }
    
    /**
     * Delete from table
     */
    public function delete($table, $where, $whereData = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        
        $this->query($sql);
        
        foreach($whereData as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        return $this->execute();
    }
    
    /**
     * Select from table
     */
    public function select($table, $fields = '*', $where = null, $whereData = [], $orderBy = null, $limit = null) {
        $sql = "SELECT {$fields} FROM {$table}";
        
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $this->query($sql);
        
        foreach($whereData as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        return $this->resultSet();
    }
    
    /**
     * Count records
     */
    public function count($table, $where = null, $whereData = []) {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        $this->query($sql);
        
        foreach($whereData as $key => $value) {
            $this->bind(":{$key}", $value);
        }
        
        $result = $this->single();
        return $result['count'];
    }
    
    /**
     * Begin Transaction
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Commit Transaction
     */
    public function commit() {
        return $this->pdo->commit();
    }
    
    /**
     * Rollback Transaction
     */
    public function rollback() {
        return $this->pdo->rollback();
    }
    
    /**
     * Check if table exists
     */
    public function tableExists($table) {
        $sql = "SHOW TABLES LIKE :table";
        $this->query($sql);
        $this->bind(':table', $table);
        $this->execute();
        return $this->rowCount() > 0;
    }
    
    /**
     * Get error
     */
    public function getError() {
        return $this->error;
    }
}
?>