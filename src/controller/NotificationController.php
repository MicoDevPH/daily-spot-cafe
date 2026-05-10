<?php
require_once __DIR__ . '/../model/Notification.php';

class NotificationController {
    private $model;

    public function __construct() {
        $this->model = new Notification();
    }

    public function getNotifications($filter = 'all') {
        return $this->model->getAll($filter);
    }

    public function getUnreadCount() {
        return $this->model->getUnreadCount();
    }

    public function markAsRead($id) {
        return $this->model->markAsRead($id);
    }

    public function markAllAsRead() {
        return $this->model->markAllAsRead();
    }

    public function deleteNotification($id) {
        return $this->model->delete($id);
    }

    public function addNotification($title, $message, $type = 'info') {
        return $this->model->create($title, $message, $type);
    }
}
?>
