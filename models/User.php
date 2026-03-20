<?php
/**
 * Modèle User
 * Gère les comptes utilisateurs, l'authentification et les profils (admin ou user).
 */
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    /**
     * Constructeur
     * Initialise la connexion à la base de données.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Crée un nouvel utilisateur (inscription)
     */
    public function create($firstname, $lastname, $email, $password, $study_level, $major, $role = 'user') {
        $query = "INSERT INTO " . $this->table_name . " 
                  (firstname, lastname, email, password_hash, study_level, major, role) 
                  VALUES (:firstname, :lastname, :email, :password_hash, :study_level, :major, :role)";

        $stmt = $this->conn->prepare($query);

        // Nettoyage et hachage du mot de passe (BCRYPT)
        $firstname = htmlspecialchars(strip_tags($firstname));
        $lastname = htmlspecialchars(strip_tags($lastname));
        $email = htmlspecialchars(strip_tags($email));
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt->bindParam(":firstname", $firstname);
        $stmt->bindParam(":lastname", $lastname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password_hash", $password_hash);
        $stmt->bindParam(":study_level", $study_level);
        $stmt->bindParam(":major", $major);
        $stmt->bindParam(":role", $role);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Définit un code de réinitialisation pour un utilisateur
     */
    public function setResetCode($email, $code) {
        $query = "UPDATE " . $this->table_name . " 
                  SET reset_code = :code, reset_expires_at = (NOW() + INTERVAL '1 hour') 
                  WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":email", $email);
        return $stmt->execute();
    }

    /**
     * Vérifie si un code de réinitialisation est valide
     */
    public function verifyResetCode($email, $code) {
        $query = "SELECT id FROM " . $this->table_name . " 
                  WHERE email = :email AND reset_code = :code AND reset_expires_at > NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":code", $code);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Définit un code de vérification à l'inscription
     */
    public function setVerificationCode($id, $code) {
        $query = "UPDATE " . $this->table_name . " SET verification_code = :code WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    /**
     * Vérifie l'email de l'utilisateur
     */
    public function verifyEmail($id, $code) {
        $query = "UPDATE " . $this->table_name . " 
                  SET is_verified = TRUE, verification_code = NULL 
                  WHERE id = :id AND verification_code = :code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":code", $code);
        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    /**
     * Recherche un utilisateur par son adresse email
     * Utilisé principalement pour le processus de connexion.
     */
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur par son ID unique
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour les informations du profil utilisateur
     */
    public function update($id, $firstname, $lastname, $email, $study_level, $major) {
        $query = "UPDATE " . $this->table_name . " 
                  SET firstname = :firstname, lastname = :lastname, email = :email, 
                      study_level = :study_level, major = :major 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        $firstname = htmlspecialchars(strip_tags($firstname));
        $lastname = htmlspecialchars(strip_tags($lastname));
        $email = htmlspecialchars(strip_tags($email));
        $study_level = htmlspecialchars(strip_tags($study_level));
        $major = htmlspecialchars(strip_tags($major));

        $stmt->bindParam(":firstname", $firstname);
        $stmt->bindParam(":lastname", $lastname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":study_level", $study_level);
        $stmt->bindParam(":major", $major);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    /**
     * Met à jour uniquement le hachage du mot de passe
     */
    public function updatePassword($id, $password_hash) {
        $query = "UPDATE " . $this->table_name . " SET password_hash = :hash WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":hash", $password_hash);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    /**
     * Récupère la liste de tous les utilisateurs (pour l'administration)
     */
    public function getAll() {
        $query = "SELECT id, firstname, lastname, email, role, created_at FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime un compte utilisateur
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    /**
     * Récupère la liste des administrateurs du système
     */
    public function getAdmins() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE role = 'admin'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie statiquement si l'utilisateur en session est un administrateur
     */
    public static function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}
