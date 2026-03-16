<?php
/**
 * Modèle Subject
 * Représente une matière d'étude avec ses caractéristiques (difficulté, coefficient, couleur).
 */
require_once __DIR__ . '/../config/database.php';

class Subject {
    private $conn;
    private $table_name = "subjects";

    /**
     * Constructeur
     * Initialise la connexion à la base de données.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Crée une nouvelle matière pour un utilisateur
     */
    public function create($user_id, $name, $difficulty, $target_grade, $coefficient, $color) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, name, difficulty_level, target_grade, coefficient, color) 
                  VALUES (:user_id, :name, :difficulty, :target, :coef, :color)";
        
        $stmt = $this->conn->prepare($query);
        
        // Nettoyage des données
        $name = htmlspecialchars(strip_tags($name));
        $color = htmlspecialchars(strip_tags($color));

        // Validation du format de la couleur (Hexadécimal)
        if (!preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            $color = '#6366f1'; // Couleur de secours (indigo) si format invalide
        }

        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":difficulty", $difficulty);
        $stmt->bindParam(":target", $target_grade);
        $stmt->bindParam(":coef", $coefficient);
        $stmt->bindParam(":color", $color);

        return $stmt->execute();
    }

    /**
     * Récupère toutes les matières d'un utilisateur, triées par nom
     */
    public function getAllByUser($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les détails d'une matière spécifique en vérifiant la propriété
     */
    public function getById($id, $user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id AND user_id = :uid LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour les informations d'une matière
     */
    public function update($id, $user_id, $name, $difficulty, $target, $coefficient, $color) {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, difficulty_level = :difficulty, target_grade = :target, 
                       coefficient = :coef, color = :color 
                  WHERE id = :id AND user_id = :uid";
        
        $stmt = $this->conn->prepare($query);

        // Nettoyage
        $name = htmlspecialchars(strip_tags($name));
        $color = htmlspecialchars(strip_tags($color));

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":difficulty", $difficulty);
        $stmt->bindParam(":target", $target);
        $stmt->bindParam(":coef", $coefficient);
        $stmt->bindParam(":color", $color);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":uid", $user_id);

        return $stmt->execute();
    }

    /**
     * Supprime une matière
     */
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND user_id = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":uid", $user_id);
        return $stmt->execute();
    }
}
