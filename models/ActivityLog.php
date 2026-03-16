<?php
/**
 * Modèle ActivityLog
 * Gère l'enregistrement et la récupération des journaux d'activité du système.
 */
require_once __DIR__ . '/../config/database.php';

class ActivityLog {
    private $conn;
    private $table_name = "activity_logs";

    /**
     * Constructeur
     * Initialise la connexion à la base de données.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Enregistre une action effectuée par un utilisateur
     */
    public function log($user_id, $action, $details = null) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, action, details) VALUES (:uid, :action, :details)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->bindParam(":action", $action);
        $stmt->bindParam(":details", $details);
        return $stmt->execute();
    }

    /**
     * Récupère les actions les plus récentes avec les noms des utilisateurs
     */
    public function getLatest($limit = 50) {
        $query = "SELECT l.*, u.firstname, u.lastname 
                  FROM " . $this->table_name . " l 
                  JOIN users u ON l.user_id = u.id 
                  ORDER BY l.created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
