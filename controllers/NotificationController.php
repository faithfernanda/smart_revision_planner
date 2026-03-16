<?php
/**
 * Contrôleur des Notifications
 * Gère la récupération du nombre de notifications non lues, la liste complète et le marquage comme lu.
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Notification.php';

class NotificationController extends Controller {
    
    /**
     * Récupère le nombre de notifications non lues
     * Utilisé pour la pastille (badge) sur l'icône de cloche.
     */
    public function count() {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['count' => 0]);
            return;
        }

        $notificationModel = new Notification();
        $count = $notificationModel->getUnreadCount($_SESSION['user_id']);
        $this->json(['count' => $count]);
    }

    /**
     * Récupère la liste de toutes les notifications de l'utilisateur
     */
    public function list() {
        if (!isset($_SESSION['user_id'])) {
            $this->json([]);
            return;
        }

        $notificationModel = new Notification();
        $notifications = $notificationModel->getAll($_SESSION['user_id']);
        $this->json($notifications);
    }

    /**
     * Marque toutes les notifications comme lues
     */
    public function readAll() {
        // Sécurité : vérifie la connexion et la méthode de requête
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false]);
            return;
        }

        $this->verifyCsrf();
        $notificationModel = new Notification();
        $success = $notificationModel->markAsRead($_SESSION['user_id']);
        $this->json(['success' => $success]);
    }
}
