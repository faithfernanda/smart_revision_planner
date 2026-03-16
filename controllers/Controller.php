<?php
/**
 * Contrôleur de Base
 * Classe parente de tous les contrôleurs du projet.
 * Fournit des méthodes utilitaires pour le rendu des vues, les redirections et la sécurité.
 */
require_once __DIR__ . '/../models/Setting.php';
require_once __DIR__ . '/../models/User.php';

class Controller {
    protected $jsonInput = null;

    /**
     * Constructeur
     * Vérifie si le mode maintenance est activé et restreint l'accès si nécessaire.
     */
    public function __construct() {
        if (Setting::isMaintenanceMode()) {
            $isAdmin = User::isAdmin();
            $currentUri = $_SERVER['REQUEST_URI'];
            // Permet l'accès aux routes d'authentification et de maintenance même en mode maintenance
            $isAuthRoute = strpos($currentUri, url('/login')) !== false || 
                           strpos($currentUri, url('/logout')) !== false || 
                           strpos($currentUri, url('/maintenance')) !== false;

            if (!$isAdmin && !$isAuthRoute) {
                $this->redirect('/maintenance');
            }
        }
    }

    /**
     * Retourne l'ID de l'utilisateur cible.
     * Si un admin utilise le paramètre user_id dans l'URL, retourne cet ID pour l'inspection.
     * Sinon, retourne l'ID de l'utilisateur actuellement sessionné.
     */
    protected function getTargetUserId() {
        if (User::isAdmin() && isset($_GET['user_id'])) {
            return (int)$_GET['user_id'];
        }
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Charge une vue avec des données
     */
    protected function view($view, $data = []) {
        extract($data); // Transforme les clés du tableau en variables
        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    /**
     * Redirige vers une URL spécifique
     */
    protected function redirect($url) {
        // Si c'est un chemin relatif, ajoute l'URL de base
        if (strpos($url, 'http') !== 0) {
            $url = url($url);
        }
        header("Location: $url");
        exit;
    }
    
    /**
     * Envoie une réponse au format JSON
     */
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Récupère les données envoyées en JSON dans le corps de la requête
     */
    protected function getJsonInput() {
        if ($this->jsonInput === null) {
            $raw = file_get_contents('php://input');
            $this->jsonInput = json_decode($raw, true) ?: [];
        }
        return $this->jsonInput;
    }

    /**
     * Vérifie la validité du jeton CSRF pour les requêtes POST
     */
    protected function verifyCsrf() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? null;
            
            // Si le jeton n'est pas dans $_POST, vérifie dans le corps JSON
            if (!$token) {
                $input = $this->getJsonInput();
                $token = $input['csrf_token'] ?? null;
            }

            // Arrête l'exécution si le jeton est manquant ou invalide
            if (!$token || !verify_csrf_token($token)) {
                die("Erreur CSRF : Session invalide ou tentative de piratage.");
            }
        }
    }
}
