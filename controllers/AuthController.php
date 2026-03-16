<?php
/**
 * Contrôleur d'Authentification
 * Gère les processus de connexion, d'inscription et de déconnexion des utilisateurs.
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class AuthController extends Controller {
    
    /**
     * Affiche le formulaire de connexion
     */
    public function loginForm() {
        $this->view('auth/login');
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function registerForm() {
        $this->view('auth/register');
    }

    /**
     * Traite la tentative de connexion
     */
    public function login() {
        $this->verifyCsrf(); // Vérification de sécurité contre les attaques CSRF
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // Vérification de l'existence de l'utilisateur et validité du mot de passe
        if ($user && password_verify($password, $user['password_hash'])) {
            // Sécurisation de la session
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['firstname'];
            $_SESSION['user_role'] = $user['role'] ?? 'user';
            
            // Journalisation de l'événement
            $log = new ActivityLog();
            $log->log($user['id'], 'LOGIN', 'Connexion réussie');

            // Redirection selon le rôle
            if ($_SESSION['user_role'] === 'admin') {
                $this->redirect('/admin');
            } else {
                $this->redirect('/dashboard');
            }
        } else {
            // Retour au formulaire avec un message d'erreur
            $this->view('auth/login', ['error' => 'Email ou mot de passe incorrect.']);
        }
    }

    /**
     * Traite l'inscription d'un nouvel utilisateur
     */
    public function register() {
        $this->verifyCsrf();
        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $study_level = $_POST['study_level'] ?? '';
        $major = $_POST['major'] ?? '';

        // Validation basique des champs obligatoires
        if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
            $this->view('auth/register', ['error' => 'Veuillez remplir tous les champs obligatoires.']);
            return;
        }

        $userModel = new User();
        // Vérifie si l'email est déjà utilisé
        if ($userModel->findByEmail($email)) {
            $this->view('auth/register', ['error' => 'Cet email est déjà utilisé.']);
            return;
        }

        // Création de l'utilisateur dans la base de données
        if ($userModel->create($firstname, $lastname, $email, $password, $study_level, $major)) {
            $newUser = $userModel->findByEmail($email);
            
            // Journalisation de la création de compte
            $log = new ActivityLog();
            $log->log($newUser['id'], 'USER_REGISTERED', "Nouvel utilisateur inscrit : $firstname $lastname");

            // Notification pour les administrateurs
            require_once __DIR__ . '/../models/Notification.php';
            $notificationModel = new Notification();
            $admins = $userModel->getAdmins();
            foreach ($admins as $admin) {
                $notificationModel->create(
                    $admin['id'], 
                    "👤 Nouvel Utilisateur", 
                    "$firstname $lastname vient de créer un compte."
                );
            }

            // Redirection vers la page de connexion après succès
            $this->redirect('/login?registered=1');
        } else {
            $this->view('auth/register', ['error' => 'Une erreur est survenue lors de l\'inscription.']);
        }
    }

    /**
     * Déconnecte l'utilisateur et détruit la session
     */
    public function logout() {
        $this->verifyCsrf();
        session_destroy();
        $this->redirect('/login');
    }
}
