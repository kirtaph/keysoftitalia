<?php
/**
 * Key Soft Italia - Flash Messages System
 * Sistema per gestire messaggi di notifica tra richieste
 */

class FlashMessage {
    
    const SUCCESS = 'success';
    const ERROR = 'error';
    const WARNING = 'warning';
    const INFO = 'info';
    
    /**
     * Initialize session if not started
     */
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
    }
    
    /**
     * Add a flash message
     */
    public static function add($message, $type = self::INFO, $dismissible = true) {
        self::init();
        
        $_SESSION['flash_messages'][] = [
            'message' => $message,
            'type' => $type,
            'dismissible' => $dismissible,
            'timestamp' => time()
        ];
    }
    
    /**
     * Add success message
     */
    public static function success($message, $dismissible = true) {
        self::add($message, self::SUCCESS, $dismissible);
    }
    
    /**
     * Add error message
     */
    public static function error($message, $dismissible = true) {
        self::add($message, self::ERROR, $dismissible);
    }
    
    /**
     * Add warning message
     */
    public static function warning($message, $dismissible = true) {
        self::add($message, self::WARNING, $dismissible);
    }
    
    /**
     * Add info message
     */
    public static function info($message, $dismissible = true) {
        self::add($message, self::INFO, $dismissible);
    }
    
    /**
     * Get all messages and clear them
     */
    public static function get() {
        self::init();
        
        $messages = $_SESSION['flash_messages'];
        $_SESSION['flash_messages'] = [];
        
        return $messages;
    }
    
    /**
     * Check if there are messages
     */
    public static function has() {
        self::init();
        return !empty($_SESSION['flash_messages']);
    }
    
    /**
     * Display messages as HTML
     */
    public static function display() {
        $messages = self::get();
        
        if (empty($messages)) {
            return '';
        }
        
        $html = '<div class="flash-messages">';
        
        foreach ($messages as $msg) {
            $typeClass = 'alert-' . $msg['type'];
            $dismissButton = '';
            
            if ($msg['dismissible']) {
                $dismissButton = '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            }
            
            $icon = self::getIcon($msg['type']);
            
            $html .= <<<HTML
            <div class="alert {$typeClass} alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <span class="alert-icon">{$icon}</span>
                    <div class="alert-message">{$msg['message']}</div>
                </div>
                {$dismissButton}
            </div>
HTML;
        }
        
        $html .= '</div>';
        
        // Add CSS if not already included
        $html .= self::getStyles();
        
        return $html;
    }
    
    /**
     * Get icon for message type
     */
    private static function getIcon($type) {
        switch ($type) {
            case self::SUCCESS:
                return '<i class="ri-checkbox-circle-line"></i>';
            case self::ERROR:
                return '<i class="ri-error-warning-line"></i>';
            case self::WARNING:
                return '<i class="ri-alert-line"></i>';
            case self::INFO:
            default:
                return '<i class="ri-information-line"></i>';
        }
    }
    
    /**
     * Get CSS styles for flash messages
     */
    private static function getStyles() {
        return <<<CSS
<style>
.flash-messages {
    position: fixed;
    top: 100px;
    right: 20px;
    z-index: 9999;
    max-width: 400px;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.flash-messages .alert {
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: none;
    border-left: 4px solid;
}

.flash-messages .alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left-color: #28a745;
}

.flash-messages .alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border-left-color: #dc3545;
}

.flash-messages .alert-warning {
    background-color: #fff3cd;
    color: #856404;
    border-left-color: #ffc107;
}

.flash-messages .alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border-left-color: #17a2b8;
}

.flash-messages .alert-icon {
    font-size: 24px;
    margin-right: 15px;
    flex-shrink: 0;
}

.flash-messages .alert-message {
    flex: 1;
}

@media (max-width: 768px) {
    .flash-messages {
        left: 10px;
        right: 10px;
        max-width: none;
    }
}
</style>
CSS;
    }
}
?>