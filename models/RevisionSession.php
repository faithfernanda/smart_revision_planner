<?php
/**
 * Modèle RevisionSession
 * Gère les séances de révision individuelles générées par le planificateur ou créées manuellement.
 */
require_once __DIR__ . '/../config/database.php';

class RevisionSession {
    private $conn;
    private $table_name = "revision_sessions";

    /**
     * Constructeur
     * Initialise la connexion à la base de données.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Récupère les prochaines séances de révision (à partir d'aujourd'hui)
     */
    public function getUpcoming($user_id, $limit = 50) {
        $query = "SELECT r.*, s.name as subject_name, s.color 
                  FROM " . $this->table_name . " r
                  JOIN subjects s ON r.subject_id = s.id
                  WHERE r.user_id = :uid AND r.start_datetime >= CURRENT_DATE
                  ORDER BY r.start_datetime ASC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère l'intégralité des séances d'un utilisateur (pour le calendrier complet)
     */
    public function getAll($user_id) {
        $query = "SELECT r.*, s.name as subject_name, s.color 
                  FROM " . $this->table_name . " r
                  JOIN subjects s ON r.subject_id = s.id
                  WHERE r.user_id = :uid
                  ORDER BY r.start_datetime ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule les statistiques d'heures de révision par matière
     */
    public function getSubjectStats($user_id) {
        // Calcule le temps total en heures pour chaque matière en extrayant l'intervalle EPOCH
        $query = "SELECT s.name, s.color, COUNT(r.id) as session_count, 
                  SUM(EXTRACT(EPOCH FROM (r.end_datetime - r.start_datetime))/3600) as total_hours
                  FROM " . $this->table_name . " r
                  JOIN subjects s ON r.subject_id = s.id
                  WHERE r.user_id = :uid
                  GROUP BY s.id, s.name, s.color";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le nombre de séances par statut (planifiée vs terminée)
     */
    public function getCompletionStats($user_id) {
        $query = "SELECT status, COUNT(*) as count FROM " . $this->table_name . " 
                  WHERE user_id = :uid 
                  GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Retourne un tableau associatif : ['planned' => 10, 'completed' => 5]
    }
    
    /**
     * Met à jour le statut d'une séance (ex: de 'planned' à 'completed')
     */
    public function updateStatus($id, $status, $user_id) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id AND user_id = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":uid", $user_id);
        return $stmt->execute();
    }

    /**
     * Supprime une séance de révision
     */
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND user_id = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":uid", $user_id);
        return $stmt->execute();
    }

    /**
     * Met à jour les horaires (début et fin) d'une séance
     */
    public function update($id, $data, $user_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET start_datetime = :start, end_datetime = :end 
                  WHERE id = :id AND user_id = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":start", $data['start_datetime']);
        $stmt->bindParam(":end", $data['end_datetime']);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":uid", $user_id);
        return $stmt->execute();
    }

    /**
     * Vérifie si une séance existe pour un utilisateur donné
     */
    public function exists($id, $user_id) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE id = :id AND user_id = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Récupère les détails d'une séance par son ID
     */
    public function getById($id, $user_id) {
        $query = "SELECT r.*, s.name as subject_name FROM " . $this->table_name . " r
                  JOIN subjects s ON r.subject_id = s.id
                  WHERE r.id = :id AND r.user_id = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Trouve la prochaine séance devant débuter bientôt (pour notifications imminentes)
     */
    public function getNextSoon($user_id, $minutes = 15) {
        // Recherche une séance débutant dans l'intervalle spécifié
        $query = "SELECT r.*, s.name as subject_name 
                  FROM " . $this->table_name . " r
                  JOIN subjects s ON r.subject_id = s.id
                  WHERE r.user_id = :uid 
                    AND r.start_datetime > NOW() 
                    AND r.start_datetime <= NOW() + (:mins || ' minutes')::interval
                    AND r.status = 'planned'
                  ORDER BY r.start_datetime ASC 
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->bindParam(":mins", $minutes);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne le nombre total de séances de révision dans le système (pour l'admin)
     */
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['total'] ?? 0;
    }

    /**
     * Calcule le taux de complétion par matière
     */
    public function getSubjectCompletionStats($user_id) {
        $query = "SELECT s.id, s.name, s.color,
                  COUNT(r.id) as total_sessions,
                  SUM(CASE WHEN r.status = 'completed' THEN 1 ELSE 0 END) as completed_sessions
                  FROM subjects s
                  LEFT JOIN " . $this->table_name . " r ON s.id = r.subject_id
                  WHERE s.user_id = :uid
                  GROUP BY s.id, s.name, s.color";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calcule le pourcentage de complétion arrondi pour chaque matière
        foreach ($stats as &$stat) {
            $stat['completion_rate'] = $stat['total_sessions'] > 0 
                ? round(($stat['completed_sessions'] / $stat['total_sessions']) * 100) 
                : 0;
        }
        
        return $stats;
    }
}
