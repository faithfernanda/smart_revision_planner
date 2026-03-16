<?php
/**
 * Modèle Notification
 * Gère la création, la vérification et la gestion des notifications destinées aux utilisateurs.
 */
require_once __DIR__ . '/../config/database.php';

class Notification {
    private $conn;
    private $table_name = "notifications";

    /**
     * Constructeur
     * Initialise la connexion à la base de données.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Crée une nouvelle notification
     */
    public function create($user_id, $title, $message, $session_id = null) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, title, message, session_id) VALUES (:uid, :title, :msg, :sid)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":msg", $message);
        $stmt->bindParam(":sid", $session_id); // Optionnel : lie la notification à une séance de révision
        return $stmt->execute();
    }

    /**
     * Vérifie si une notification existe déjà pour une séance spécifique
     * Utile pour éviter d'envoyer plusieurs rappels pour la même séance.
     */
    public function existsForSession($user_id, $session_id) {
        if (!$session_id) return false;
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE user_id = :uid AND session_id = :sid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->bindParam(":sid", $session_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Compte le nombre de notifications non lues pour un utilisateur
     */
    public function getUnreadCount($user_id) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE user_id = :uid AND is_read = FALSE";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Récupère les notifications les plus récentes d'un utilisateur
     */
    public function getAll($user_id, $limit = 20) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :uid ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Marque une notification spécifique ou TOUTES les notifications d'un utilisateur comme lues
     */
    public function markAsRead($user_id, $notification_id = null) {
        if ($notification_id) {
            // Marque une notification précise
            $query = "UPDATE " . $this->table_name . " SET is_read = TRUE WHERE id = :id AND user_id = :uid";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $notification_id);
            $stmt->bindParam(":uid", $user_id);
        } else {
            // Marque tout comme lu (utilisé lors de l'ouverture du panneau de notifications)
            $query = "UPDATE " . $this->table_name . " SET is_read = TRUE WHERE user_id = :uid";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":uid", $user_id);
        }
        return $stmt->execute();
    }
}
