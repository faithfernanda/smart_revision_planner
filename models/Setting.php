<?php
/**
 * Modèle Setting
 * Gère le stockage et la récupération des paramètres de configuration globale en base de données.
 */
require_once __DIR__ . '/../config/database.php';

class Setting {
    private $conn;
    private $table_name = "settings";

    /**
     * Constructeur
     * Initialise la connexion à la base de données.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Récupère la valeur d'un paramètre par sa clé
     */
    public function get($key, $default = null) {
        $query = "SELECT value FROM " . $this->table_name . " WHERE key = :key";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":key", $key);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['value'] : $default;
    }

    /**
     * Définit ou met à jour la valeur d'un paramètre
     * Utilise ON CONFLICT pour gérer l'insertion ou la mise à jour atomique.
     */
    public function set($key, $value) {
        $query = "INSERT INTO " . $this->table_name . " (key, value, updated_at) 
                  VALUES (:key, :value, CURRENT_TIMESTAMP) 
                  ON CONFLICT (key) DO UPDATE SET value = :value, updated_at = CURRENT_TIMESTAMP";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":key", $key);
        $stmt->bindParam(":value", $value);
        return $stmt->execute();
    }

    /**
     * Vérifie statiquement si le mode maintenance est activé
     */
    public static function isMaintenanceMode() {
        $instance = new self();
        return $instance->get('maintenance_mode', 'off') === 'on';
    }

    /**
     * Récupère statiquement le nom du site
     */
    public static function getSiteName() {
        $instance = new self();
        return $instance->get('site_name', 'Smart Revision');
    }
}
