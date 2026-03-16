<?php
/**
 * Modèle Exam
 * Représente une échéance d'examen liée à une matière spécifique.
 */
require_once __DIR__ . '/../config/database.php';

class Exam {
    private $conn;
    private $table_name = "exams";

    /**
     * Constructeur
     * Initialise la connexion à la base de données.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Enregistre un nouvel examen
     */
    public function create($subject_id, $date, $time) {
        $query = "INSERT INTO " . $this->table_name . " (subject_id, exam_date, exam_time) VALUES (:sid, :date, :time)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":sid", $subject_id);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":time", $time);
        return $stmt->execute();
    }

    /**
     * Récupère tous les examens à venir d'un utilisateur, avec les détails de la matière associée
     */
    public function getUpcoming($user_id) {
        // Jointure avec la table subjects pour vérifier la propriété et récupérer le nom de la matière
        $query = "SELECT e.*, s.name as subject_name, s.difficulty_level, s.coefficient 
                  FROM " . $this->table_name . " e
                  JOIN subjects s ON e.subject_id = s.id
                  WHERE s.user_id = :uid AND e.exam_date >= CURRENT_DATE
                  ORDER BY e.exam_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un examen par son ID, en vérifiant qu'il appartient bien à l'utilisateur spécifié
     */
    public function getById($id, $user_id) {
        $query = "SELECT e.*, s.user_id FROM " . $this->table_name . " e
                  JOIN subjects s ON e.subject_id = s.id
                  WHERE e.id = :id AND s.user_id = :uid";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour les informations d'un examen existant
     */
    public function update($id, $subject_id, $date, $time) {
        $query = "UPDATE " . $this->table_name . " SET subject_id = :sid, exam_date = :date, exam_time = :time WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":sid", $subject_id);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":time", $time);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    /**
     * Supprime un examen par son ID
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
