<?php
/**
 * Database Connection Class
 * Singleton pattern for MySQL connection
 */

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $this->connect();
    }
    
    /**
     * Get database instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Connect to database
     */
    private function connect() {
        try {
            // Database configuration
            $host = defined('DB_HOST') ? DB_HOST : 'localhost';
            $user = defined('DB_USER') ? DB_USER : 'root';
            $pass = defined('DB_PASS') ? DB_PASS : '';
            $name = defined('DB_NAME') ? DB_NAME : 'keysoftitalia';
            $port = defined('DB_PORT') ? DB_PORT : 3306;
            $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';
            
            // Create connection
            $this->connection = new mysqli($host, $user, $pass, $name, $port);
            
            // Check connection
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }
            
            // Set charset
            if (!$this->connection->set_charset($charset)) {
                throw new Exception("Error loading character set $charset: " . $this->connection->error);
            }
            
            // Set timezone
            $this->connection->query("SET time_zone = '+01:00'");
            
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            // In production, show a friendly error page
            if (!defined('DEBUG_MODE') || !DEBUG_MODE) {
                die("Errore di connessione al database. Riprova più tardi.");
            } else {
                die("Database Error: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Get connection object
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Execute query
     */
    public function query($sql) {
        $result = $this->connection->query($sql);
        if ($this->connection->error) {
            error_log("Query error: " . $this->connection->error);
            throw new Exception("Query error: " . $this->connection->error);
        }
        return $result;
    }
    
    /**
     * Prepare statement
     */
    public function prepare($sql) {
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            error_log("Prepare error: " . $this->connection->error);
            throw new Exception("Prepare error: " . $this->connection->error);
        }
        return $stmt;
    }
    
    /**
     * Get last insert ID
     */
    public function lastInsertId() {
        return $this->connection->insert_id;
    }
    
    /**
     * Escape string
     */
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction() {
        $this->connection->autocommit(false);
    }
    
    /**
     * Commit transaction
     */
    public function commit() {
        $this->connection->commit();
        $this->connection->autocommit(true);
    }
    
    /**
     * Rollback transaction
     */
    public function rollback() {
        $this->connection->rollback();
        $this->connection->autocommit(true);
    }
    
    /**
     * Close connection
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    /**
     * Destructor
     */
    public function __destruct() {
        $this->close();
    }
    
    /**
     * Prevent cloning
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Helper function to get database instance
 */
function db() {
    return Database::getInstance();
}

/**
 * Helper function to sanitize input
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Helper function to send email
 */
function send_email($to, $subject, $body, $from = null) {
    if ($from === null) {
        $from = defined('EMAIL_NOREPLY') ? EMAIL_NOREPLY : 'noreply@keysoftitalia.it';
    }
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . SITE_NAME . ' <' . $from . '>',
        'Reply-To: ' . $from,
        'X-Mailer: PHP/' . phpversion()
    ];
    
    // In development, log emails instead of sending
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        error_log("Email to: $to");
        error_log("Subject: $subject");
        error_log("Body: $body");
        return true;
    }
    
    return mail($to, $subject, $body, implode("\r\n", $headers));
}

/**
 * Helper function to format date
 */
function format_date($date, $format = 'd/m/Y H:i') {
    if (is_string($date)) {
        $date = strtotime($date);
    }
    return date($format, $date);
}

/**
 * Helper function to generate unique ID
 */
function generate_unique_id($prefix = '') {
    return $prefix . uniqid() . bin2hex(random_bytes(8));
}

/**
 * Helper function to validate Italian fiscal code
 */
function validate_fiscal_code($code) {
    $code = strtoupper(trim($code));
    return preg_match('/^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$/', $code);
}

/**
 * Helper function to validate Italian VAT number
 */
function validate_vat_number($vat) {
    $vat = trim($vat);
    $vat = str_replace(['IT', 'it', ' '], '', $vat);
    return preg_match('/^[0-9]{11}$/', $vat);
}
?>