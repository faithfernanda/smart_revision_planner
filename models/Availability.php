<?php
/**
 * Modèle Availability
 * Représente les créneaux hebdomadaires de disponibilité d'un utilisateur.
 */
require_once __DIR__ . '/../config/database.php';

class Availability {
    private $conn;
    private $table_name = "availabilities";

    /**
     * Constructeur
     * Initialise la connexion à la base de données.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Crée un nouveau créneau de disponibilité
     */
    public function create($user_id, $day, $start, $end) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, day_of_week, start_time, end_time) 
                  VALUES (:uid, :day, :start, :end)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->bindParam(":day", $day); // Index du jour (0 pour Dimanche, etc.)
        $stmt->bindParam(":start", $start);
        $stmt->bindParam(":end", $end);
        return $stmt->execute();
    }

    /**
     * Récupère tous les créneaux d'un utilisateur, triés par jour et par heure
     */
    public function getAll($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :uid ORDER BY day_of_week, start_time";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime un créneau spécifique appartenant à un utilisateur
     */
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND user_id = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":uid", $user_id);
        return $stmt->execute();
    }
}
