<?php
/**
 * Contrôleur Pomodoro
 * Gère le minuteur de productivité et l'enregistrement automatique des sessions terminées.
 */
require_once __DIR__ . '/Controller.php';

class PomodoroController extends Controller {
    
    /**
     * Affiche l'interface du minuteur Pomodoro
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        
        require_once __DIR__ . '/../models/Subject.php';
        $subjectModel = new Subject();
        $subjects = $subjectModel->getAllByUser($_SESSION['user_id']); // Liste des matières pour lier la session

        $this->view('pomodoro/index', ['subjects' => $subjects]);
    }

    /**
     * Enregistre une session de révision de type Pomodoro terminée
     * Reçoit les données en JSON depuis l'interface JavaScript.
     */
    public function save() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false]);
        }
        
        $this->verifyCsrf();
        $input = $this->getJsonInput(); // Extraction des données JSON du corps de la requête
        
        $subject_id = $input['subject_id'] ?? null;
        $duration = $input['duration'] ?? 25; // Durée par défaut de 25 minutes

        if ($subject_id) {
            require_once __DIR__ . '/../models/RevisionSession.php';
            
            $db = new Database();
            $conn = $db->getConnection();
            
            // Calcul des temps de début et de fin
            $start = date('Y-m-d H:i:s', strtotime("-$duration minutes"));
            $end = date('Y-m-d H:i:s');
            
            // Insertion directe d'une session marquée comme 'completed'
            $query = "INSERT INTO revision_sessions (user_id, subject_id, start_datetime, end_datetime, status) 
                      VALUES (:uid, :sid, :start, :end, 'completed')";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":uid", $_SESSION['user_id']);
            $stmt->bindParam(":sid", $subject_id);
            $stmt->bindParam(":start", $start);
            $stmt->bindParam(":end", $end);
            
            if ($stmt->execute()) {
                $this->json(['success' => true]);
            } else {
                $this->json(['success' => false]);
            }
        } else {
            $this->json(['success' => false, 'message' => 'Aucune matière sélectionnée']);
        }
    }
}
