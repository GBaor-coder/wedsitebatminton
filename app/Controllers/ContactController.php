<?php
/**
 * Contact Controller - Admin management for customer messages
 */

require_once __DIR__ . '/AdminBaseController.php';

class ContactController extends AdminBaseController {
    
    public function __construct($params = []) {
        parent::__construct($params);
    }
    
    /**
     * List all contacts
     */
    public function index() {
        $db = \Database::getInstance();
        $contacts = $db->fetchAll("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 50");
        
        $data = [
            'title' => 'Tin nhắn khách hàng',
            'contacts' => $contacts
        ];
        $this->render('contacts/index', $data);
    }
    
    /**
     * Messenger interface
     */
    public function messenger() {
        $db = \Database::getInstance();
        $contacts = $db->fetchAll("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 20") ?: [];
        
        // Stats
$total = $db->fetchOne("SELECT COUNT(*) as count FROM contacts")['count'] ?? 0;
$today = $db->fetchOne("SELECT COUNT(*) as count FROM contacts WHERE DATE(created_at) = CURDATE()")['count'] ?? 0;
$phone_count = $db->fetchOne("SELECT COUNT(*) as count FROM contacts WHERE phone IS NOT NULL AND phone != ''")['count'] ?? 0;
$unread_count = 0; // No status column, all considered unread
        
        $data = [
            'title' => 'Messenger',
            'contacts' => $contacts,
            'total_messages' => $total,
            'today_messages' => $today,
            'phone_count' => $phone_count,
            'unread_count' => $unread_count
        ];
        $this->render('contacts/messenger', $data);
    }
}
?>

