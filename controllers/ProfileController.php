<?php
/**
 * Contrôleur de Profil
 * Gère l'affichage des informations personnelles de l'utilisateur et la mise à jour de ses données (y compris mot de passe).
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController extends Controller {
    
    /**
     * Affiche le profil de l'utilisateur
     * Charge également l'historique des notifications pour consultation.
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        
        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);
        
        require_once __DIR__ . '/../models/Notification.php';
        $notificationModel = new Notification();
        $notifications = $notificationModel->getAll($_SESSION['user_id']);

        $this->view('profile/index', [
            'user' => $user,
            'notifications' => $notifications
        ]);
    }

    /**
     * Met à jour les informations du profil (nom, email, niveau d'étude, etc.)
     */
    public function update() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        $this->verifyCsrf();

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $study_level = $_POST['study_level'];
        $major = $_POST['major'];

        $userModel = new User();
        if ($userModel->update($_SESSION['user_id'], $firstname, $lastname, $email, $study_level, $major)) {
            // Met à jour le nom dans la session pour refléter le changement immédiatement dans l'interface
            $_SESSION['user_name'] = $firstname;
            $this->redirect('/profile?msg=updated');
        } else {
            $this->redirect('/profile?error=update_failed');
        }
    }

    /**
     * Gère le changement sécurisé du mot de passe
     */
    public function updatePassword() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        $this->verifyCsrf();

        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Vérification élémentaire de la confirmation
        if ($new_password !== $confirm_password) {
            $this->redirect('/profile?error=password_mismatch');
            return;
        }

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);

        // Vérifie si le mot de passe actuel saisi correspond à celui en base
        if (password_verify($current_password, $user['password_hash'])) {
            // Hachage sécurisé du nouveau mot de passe (BCRYPT)
            $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
            if ($userModel->updatePassword($_SESSION['user_id'], $new_hash)) {
                $this->redirect('/profile?msg=password_updated');
            } else {
                $this->redirect('/profile?error=update_failed');
            }
        } else {
            // Erreur si le mot de passe actuel est incorrect
            $this->redirect('/profile?error=wrong_password');
        }
    }
}
