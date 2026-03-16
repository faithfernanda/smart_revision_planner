<?php
/**
 * Contrôleur des Matières
 * Permet de gérer les disciplines étudiées (nom, difficulté, coefficient, couleur personnalisée).
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class SubjectController extends Controller {
    
    /**
     * Liste toutes les matières de l'utilisateur
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        
        $user_id = $this->getTargetUserId();
        $is_admin_view = (User::isAdmin() && isset($_GET['user_id']));
        $user_name = $_SESSION['user_name'];

        if ($is_admin_view) {
            $userModel = new User();
            $targetUser = $userModel->getById($user_id);
            if ($targetUser) {
                $user_name = $targetUser['firstname'] . ' ' . $targetUser['lastname'];
            }
        }
        
        $subjectModel = new Subject();
        $subjects = $subjectModel->getAllByUser($user_id);
        
        $this->view('subjects/index', [
            'subjects' => $subjects,
            'is_admin_view' => $is_admin_view,
            'user_name' => $user_name
        ]);
    }

    /**
     * Création d'une nouvelle matière
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        if (User::isAdmin()) $this->redirect('/admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $name = $_POST['name'];
            $difficulty = $_POST['difficulty']; // Échelle de 1 à 5
            $target = $_POST['target']; // Note visée
            $coef = $_POST['coefficient']; // Poids de la matière
            $color = $_POST['color']; // Couleur pour le calendrier
            
            $subjectModel = new Subject();
            if ($subjectModel->create($_SESSION['user_id'], $name, $difficulty, $target, $coef, $color)) {
                // Journalisation
                $log = new ActivityLog();
                $log->log($_SESSION['user_id'], 'CREATE_SUBJECT', "Sujet créé : $name");
                $this->redirect('/subjects?msg=created');
                return;
            } else {
                $this->view('subjects/create', ['error' => 'Erreur lors de la création']);
                return;
            }
        }
        
        $this->view('subjects/create');
    }

    /**
     * Affiche le formulaire de modification d'une matière
     */
    public function edit() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        if (User::isAdmin()) $this->redirect('/admin');
        
        $id = $_GET['id'] ?? null;
        if (!$id) $this->redirect('/subjects');

        $subjectModel = new Subject();
        $subject = $subjectModel->getById($id, $_SESSION['user_id']);

        // Vérification de sécurité (existence et propriété)
        if (!$subject) $this->redirect('/subjects');

        $this->view('subjects/edit', ['subject' => $subject]);
    }

    /**
     * Traite la mise à jour des informations d'une matière
     */
    public function update() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        if (User::isAdmin()) $this->redirect('/admin');
        $this->verifyCsrf();

        $id = $_POST['id'];
        $name = $_POST['name'];
        $difficulty = $_POST['difficulty'];
        $target = $_POST['target'];
        $coef = $_POST['coefficient'];
        $color = $_POST['color'];

        $subjectModel = new Subject();
        if ($subjectModel->update($id, $_SESSION['user_id'], $name, $difficulty, $target, $coef, $color)) {
            $this->redirect('/subjects?msg=updated');
        } else {
            $this->redirect("/subjects/edit?id=$id&error=update_failed");
        }
    }

    /**
     * Supprime une matière
     */
    public function delete() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        if (User::isAdmin()) $this->redirect('/admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $id = $_POST['id'];
            
            $subjectModel = new Subject();
            if ($subjectModel->delete($id, $_SESSION['user_id'])) {
                // Journalisation de la suppression
                $log = new ActivityLog();
                $log->log($_SESSION['user_id'], 'DELETE_SUBJECT', "Sujet supprimé (ID: $id)");
                $this->redirect('/subjects?msg=deleted');
            } else {
                $this->redirect('/subjects?error=delete_failed');
            }
        }
    }
}
