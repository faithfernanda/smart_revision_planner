<?php
/**
 * Contrôleur du Tableau de Bord
 * Gère l'affichage principal des activités de l'utilisateur et la génération automatique du planning.
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/Exam.php';
require_once __DIR__ . '/../models/RevisionSession.php';
require_once __DIR__ . '/../models/ActivityLog.php';
require_once __DIR__ . '/../utils/SchedulerService.php';

class DashboardController extends Controller {
    
    /**
     * Page d'accueil du tableau de bord
     * Affiche les matières, les examens à venir et les prochaines sessions de révision.
     */
    public function index() {
        // Sécurité : redirection si non connecté
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        // Récupère l'ID de l'utilisateur (soi-même ou cible admin)
        $user_id = $this->getTargetUserId();
        if (!$user_id) $this->redirect('/login');

        $user_name = $_SESSION['user_name'];
        $is_admin_view = false;

        // Logique spécifique à l'administrateur inspectant un utilisateur
        if (User::isAdmin()) {
            if (isset($_GET['user_id'])) {
                $userModel = new User();
                $targetUser = $userModel->getById($user_id);
                if ($targetUser) {
                    $user_name = $targetUser['firstname'] . ' ' . $targetUser['lastname'];
                    $is_admin_view = true;
                }
            } else {
                // Un admin sans paramètre user_id est redirigé vers son propre panel admin
                $this->redirect('/admin');
            }
        }
        
        // Initialisation des modèles pour récupérer les données nécessaires
        $subjectModel = new Subject();
        $examModel = new Exam();
        $revisionModel = new RevisionSession();
        
        $subjects = $subjectModel->getAllByUser($user_id); // Liste des matières étudiées
        $exams = $examModel->getUpcoming($user_id); // Examens imminents
        $revisions = $revisionModel->getUpcoming($user_id); // Sessions de révision calées

        // Rendu de la vue avec toutes les données agrégées
        $this->view('dashboard/index', [
            'user_name' => $user_name,
            'subjects' => $subjects,
            'exams' => $exams,
            'revisions' => $revisions,
            'is_admin_view' => $is_admin_view
        ]);
    }
    
    /**
     * Génération du planning de révision
     * Utilise le SchedulerService pour créer des sessions basées sur les disponibilités et les examens.
     */
    public function generate() {
        if (!isset($_SESSION['user_id'])) {
             $this->json(['success' => false, 'message' => 'Unauthorized']);
        }
        // Un admin ne génère pas de planning pour lui-même via cette route
        if (User::isAdmin()) {
            $this->redirect('/admin');
        }
        $this->verifyCsrf();
        
        $scheduler = new SchedulerService();
        $success = $scheduler->generateSchedule($_SESSION['user_id']);
        
        if ($success) {
            // Journalisation de la régénération du planning
            $log = new ActivityLog();
            $log->log($_SESSION['user_id'], 'SCHEDULE_GENERATED', "Planning régénéré par l'utilisateur");

            // Notification des administrateurs du système
            require_once __DIR__ . '/../models/Notification.php';
            $notificationModel = new Notification();
            $userModel = new User();
            $admins = $userModel->getAdmins();
            $userName = $_SESSION['user_name'];
            
            foreach ($admins as $admin) {
                $notificationModel->create(
                    $admin['id'], 
                    "📅 Planning Généré", 
                    "$userName vient de générer son planning de révision."
                );
            }

            $this->redirect('/dashboard?msg=schedule_generated');
        } else {
            $this->redirect('/dashboard?error=generation_failed');
        }
    }
}
