<?php
/**
 * Contrôleur d'Authentification
 * Gère les processus de connexion, d'inscription et de déconnexion des utilisateurs.
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/ActivityLog.php';
require_once __DIR__ . '/../utils/MailService.php';

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
            // Vérifier si le compte est vérifié (Exemption pour les administrateurs)
            if ($user['role'] !== 'admin' && isset($user['is_verified']) && !$user['is_verified']) {
                $this->redirect('/verify-email?id=' . $user['id'] . '&resend=1');
                return;
            }

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
        $userId = $userModel->create($firstname, $lastname, $email, $password, $study_level, $major);
        if ($userId) {
            // Générer et envoyer le code de vérification
            $verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $userModel->setVerificationCode($userId, $verification_code);

            $mailService = new MailService();
            $subject = "Bienvenue ! Vérifiez votre compte";
            $message = "Bonjour $firstname,<br><br>Merci de vous être inscrit. Votre code de vérification est : <b>$verification_code</b>";
            $mailService->send($email, $subject, $message);

            // Journalisation de la création de compte
            $log = new ActivityLog();
            $log->log($userId, 'USER_REGISTERED', "Nouvel utilisateur inscrit (en attente de vérification) : $firstname $lastname");

            // Notification pour les administrateurs
            require_once __DIR__ . '/../models/Notification.php';
            $notificationModel = new Notification();
            $admins = $userModel->getAdmins();
            foreach ($admins as $admin) {
                $notificationModel->create(
                    $admin['id'], 
                    "👤 Nouvel Utilisateur", 
                    "$firstname $lastname vient de créer un compte (non vérifié)."
                );
            }

            // Redirection vers la page de vérification
            $this->redirect('/verify-email?id=' . $userId);
        } else {
            $this->view('auth/register', ['error' => 'Une erreur est survenue lors de l\'inscription.']);
        }
    }

    /**
     * Affiche le formulaire de vérification d'email
     */
    public function verifyEmailForm() {
        $id = $_GET['id'] ?? null;
        if (!$id) $this->redirect('/login');
        $this->view('auth/verify_email', ['id' => $id, 'resend' => isset($_GET['resend'])]);
    }

    /**
     * Traite la vérification de l'email
     */
    public function verifyEmail() {
        $id = $_POST['user_id'] ?? null;
        $code = $_POST['code'] ?? '';

        $userModel = new User();
        if ($userModel->verifyEmail($id, $code)) {
            $this->redirect('/login?verified=1');
        } else {
            $this->view('auth/verify_email', ['id' => $id, 'error' => 'Code de vérification incorrect ou expiré.']);
        }
    }

    /**
     * Renvoie le code de vérification par email
     */
    public function resendVerificationCode() {
        $id = $_GET['id'] ?? null;
        if (!$id) $this->redirect('/login');

        $userModel = new User();
        $user = $userModel->getById($id);

        if ($user && !$user['is_verified']) {
            $verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $userModel->setVerificationCode($id, $verification_code);

            $mailService = new MailService();
            $subject = "Votre nouveau code de vérification";
            $message = "Bonjour " . $user['firstname'] . ",<br><br>Voici votre nouveau code de vérification : <b>$verification_code</b>";
            $mailService->send($user['email'], $subject, $message);
        }

        $this->redirect('/verify-email?id=' . $id . '&resend=1');
    }

    /**
     * Affiche le formulaire de mot de passe oublié
     */
    public function forgotPasswordForm() {
        $this->view('auth/forgot_password');
    }

    /**
     * Traite la demande de réinitialisation (envoi du code)
     */
    public function forgotPassword() {
        $this->verifyCsrf();
        $email = $_POST['email'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user) {
            $reset_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $userModel->setResetCode($email, $reset_code);

            $mailService = new MailService();
            $subject = "Réinitialisation de votre mot de passe";
            $message = "Bonjour,<br><br>Vous avez demandé la réinitialisation de votre mot de passe. Votre code de sécurité est : <b>$reset_code</b>";
            $mailService->send($email, $subject, $message);
        }

        // On affiche toujours un message de succès par sécurité (ne pas révéler si un email existe)
        $this->view('auth/verify_reset_code', ['email' => $email]);
    }

    /**
     * Traite la vérification du code de réinitialisation
     */
    public function verifyResetCode() {
        $email = $_POST['email'] ?? '';
        $code = $_POST['code'] ?? '';

        $userModel = new User();
        if ($userModel->verifyResetCode($email, $code)) {
            $this->view('auth/reset_password', ['email' => $email, 'code' => $code]);
        } else {
            $this->view('auth/verify_reset_code', ['email' => $email, 'error' => 'Code de vérification incorrect ou expiré.']);
        }
    }

    /**
     * Traite le changement effectif du mot de passe
     */
    public function resetPassword() {
        $this->verifyCsrf();
        $email = $_POST['email'] ?? '';
        $code = $_POST['code'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($password !== $confirm) {
            $this->view('auth/reset_password', ['email' => $email, 'code' => $code, 'error' => 'Les mots de passe ne correspondent pas.']);
            return;
        }

        $userModel = new User();
        if ($userModel->verifyResetCode($email, $code)) {
            $user = $userModel->findByEmail($email);
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $userModel->updatePassword($user['id'], $password_hash);
            
            // On nettoie le code de réinitialisation
            $userModel->setResetCode($email, null);

            $this->redirect('/login?reset=1');
        } else {
            $this->redirect('/login');
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
