<?php
/**
 * Contrôleur des Examens
 * Gère la consultation, la création, la modification et la suppression des échéances d'examens.
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Exam.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class ExamController extends Controller {
    
    /**
     * Liste les examens à venir de l'utilisateur
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        
        $user_id = $this->getTargetUserId();
        $is_admin_view = (User::isAdmin() && isset($_GET['user_id']));
        $user_name = $_SESSION['user_name'];

        // Informations utilisateur pour la vue
        if ($is_admin_view) {
            $userModel = new User();
            $targetUser = $userModel->getById($user_id);
            if ($targetUser) {
                $user_name = $targetUser['firstname'] . ' ' . $targetUser['lastname'];
            }
        }

        $examModel = new Exam();
        $exams = $examModel->getUpcoming($user_id); // Récupère les examens triés par date
        
        $this->view('exams/index', [
            'exams' => $exams,
            'is_admin_view' => $is_admin_view,
            'user_name' => $user_name
        ]);
    }

    /**
     * Formulaire et traitement de la création d'un examen
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        if (User::isAdmin()) $this->redirect('/admin');
        
        $user_id = $this->getTargetUserId();
        $subjectModel = new Subject();
        $subjects = $subjectModel->getAllByUser($user_id); // Nécessaire pour lier l'examen à une matière

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $subject_id = $_POST['subject_id'];
            $date = $_POST['date'];
            $time = $_POST['time'];
            
            $examModel = new Exam();
            if ($examModel->create($subject_id, $date, $time)) {
                // Journalisation de l'action
                $log = new ActivityLog();
                $log->log($_SESSION['user_id'], 'CREATE_EXAM', "Examen créé (Sujet ID: $subject_id)");
                $this->redirect('/exams?msg=created');
            } else {
                $this->view('exams/create', ['subjects' => $subjects, 'error' => 'Erreur lors de la création']);
            }
        } else {
            $this->view('exams/create', ['subjects' => $subjects]);
        }
    }

    /**
     * Affiche le formulaire d'édition d'un examen
     */
    public function edit() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        if (User::isAdmin()) $this->redirect('/admin');
        
        $user_id = $this->getTargetUserId();
        $id = $_GET['id'] ?? null;
        if (!$id) $this->redirect('/exams');

        $examModel = new Exam();
        $exam = $examModel->getById($id, $user_id);

        // Vérifie que l'examen existe et appartient bien à l'utilisateur
        if (!$exam) $this->redirect('/exams');

        $subjectModel = new Subject();
        $subjects = $subjectModel->getAllByUser($user_id);

        $this->view('exams/edit', ['exam' => $exam, 'subjects' => $subjects]);
    }

    /**
     * Traite la mise à jour d'un examen
     */
    public function update() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        if (User::isAdmin()) $this->redirect('/admin');
        $this->verifyCsrf();

        $id = $_POST['id'];
        $subject_id = $_POST['subject_id'];
        $date = $_POST['date'];
        $time = $_POST['time'];

        $examModel = new Exam();
        // Double vérification de la propriété pour plus de sécurité
        $exam = $examModel->getById($id, $_SESSION['user_id']);
        if (!$exam) $this->redirect('/exams?error=unauthorized');

        if ($examModel->update($id, $subject_id, $date, $time)) {
            $this->redirect('/exams?msg=updated');
        } else {
            $this->redirect("/exams/edit?id=$id&error=update_failed");
        }
    }

    /**
     * Supprime un examen
     */
    public function delete() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        if (User::isAdmin()) $this->redirect('/admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $id = $_POST['id'];
            
            $examModel = new Exam();
            // Vérifie la propriété avant suppression
            $exam = $examModel->getById($id, $_SESSION['user_id']);
            if (!$exam) $this->redirect('/exams?error=unauthorized');

            if ($examModel->delete($id)) {
                $log = new ActivityLog();
                $log->log($_SESSION['user_id'], 'DELETE_EXAM', "Examen supprimé (ID: $id)");
                $this->redirect('/exams?msg=deleted');
            } else {
                $this->redirect('/exams?error=delete_failed');
            }
        }
    }
}
