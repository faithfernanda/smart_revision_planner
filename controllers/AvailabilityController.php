<?php
/**
 * Contrôleur de Disponibilité
 * Gère les créneaux horaires où l'étudiant est disponible pour réviser.
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Availability.php';

class AvailabilityController extends Controller {
    
    /**
     * Liste les disponibilités de l'utilisateur
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        
        // Détermine l'utilisateur cible (self ou inspecté par admin)
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
        
        $avModel = new Availability();
        $availabilities = $avModel->getAll($user_id);
        
        // Groupement des disponibilités par jour de la semaine pour l'affichage
        $grouped = [];
        $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        foreach($availabilities as $av) {
            $dayName = $days[$av['day_of_week']];
            $grouped[$dayName][] = $av;
        }

        $this->view('availability/index', [
            'availabilities' => $grouped,
            'is_admin_view' => $is_admin_view,
            'user_name' => $user_name
        ]);
    }

    /**
     * Ajout d'une nouvelle disponibilité
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        if (User::isAdmin()) $this->redirect('/admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $day = $_POST['day'];
            $start = $_POST['start_time'];
            $end = $_POST['end_time'];
            
            $avModel = new Availability();
            if ($avModel->create($_SESSION['user_id'], $day, $start, $end)) {
                $this->redirect('/availability?msg=created');
            } else {
                $this->view('availability/create', ['error' => 'Erreur lors de l\'ajout']);
            }
        } else {
            $this->view('availability/create');
        }
    }

    /**
     * Suppression d'une disponibilité
     */
    public function delete() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        if (User::isAdmin()) $this->redirect('/admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $id = $_POST['id'];
            
            $avModel = new Availability();
            // Assure que l'utilisateur ne supprime que ses propres disponibilités
            if ($avModel->delete($id, $_SESSION['user_id'])) {
                $this->redirect('/availability?msg=deleted');
            } else {
                $this->redirect('/availability?error=delete_failed');
            }
        }
    }
}
