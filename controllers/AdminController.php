<?php
/**
 * Contrôleur d'administration
 * Gère les fonctionnalités réservées aux administrateurs (dashboard, gestion des utilisateurs, santé du système).
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/ActivityLog.php';
require_once __DIR__ . '/../models/RevisionSession.php';

class AdminController extends Controller {
    
    /**
     * Constructeur
     * Vérifie si l'utilisateur est un administrateur avant toute action.
     */
    public function __construct() {
        parent::__construct();
        // Si l'utilisateur n'est pas admin, redirection vers le tableau de bord classique
        if (!User::isAdmin()) {
            $this->redirect('/dashboard');
        }
    }

    /**
     * Page d'accueil de l'administration
     * Affiche les statistiques globales, les journaux d'activité et l'état du système.
     */
    public function index() {
        $userModel = new User();
        $users = $userModel->getAll(); // Récupère tous les utilisateurs
        
        $logModel = new ActivityLog();
        $logs = $logModel->getLatest(10); // Récupère les 10 dernières actions effectuées sur le système

        $sessionModel = new RevisionSession();
        $totalSessions = $sessionModel->getTotalCount(); // Nombre total de sessions de révision générées

        $healthStatus = $this->getHealthStatus(); // Vérification de l'état de santé de la base de données
        
        // Chargement de la vue admin avec les données collectées
        $this->view('admin/dashboard', [
            'totalUsers' => count($users),
            'totalSessions' => $totalSessions,
            'logs' => $logs,
            'health' => $healthStatus
        ]);
    }

    /**
     * Vérifie la santé du système
     * Teste la connexion à la base de données et la présence des tables essentielles.
     */
    protected function getHealthStatus() {
        $db = (new Database())->getConnection();
        $status = ['ok' => true, 'messages' => []];

        try {
            // Test simple de requête
            $db->query("SELECT 1");
        } catch (Exception $e) {
            $status['ok'] = false;
            $status['messages'][] = "Erreur DB: " . $e->getMessage();
        }

        // Liste des tables critiques à vérifier
        $tables = ['users', 'subjects', 'revision_sessions', 'settings', 'activity_logs'];
        foreach ($tables as $table) {
            try {
                // Vérifie si la table existe et est accessible
                $db->query("SELECT 1 FROM $table LIMIT 1");
            } catch (Exception $e) {
                $status['ok'] = false;
                $status['messages'][] = "Table manquante: $table";
            }
        }

        return $status;
    }

    /**
     * Gestion des utilisateurs
     * Liste tous les utilisateurs inscrits sur la plateforme.
     */
    public function users() {
        $userModel = new User();
        $users = $userModel->getAll();
        
        $this->view('admin/users', [
            'users' => $users
        ]);
    }

    /**
     * Suppression d'un utilisateur
     * Protégé contre les attaques CSRF et empêche un admin de se supprimer lui-même.
     */
    public function deleteUser() {
        $this->verifyCsrf(); // Sécurité : vérification du jeton CSRF
        
        $userId = $_POST['id'] ?? null;
        // Un administrateur ne peut pas supprimer son propre compte via cette interface
        if ($userId && $userId != $_SESSION['user_id']) {
            $userModel = new User();
            $userModel->delete($userId);
            $this->redirect('/admin/users?msg=deleted');
        } else {
            $this->redirect('/admin/users?error=cannot_delete_self');
        }
    }
}
