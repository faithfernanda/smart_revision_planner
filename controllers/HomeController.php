<?php
/**
 * Contrôleur Principal (Home)
 * Point d'entrée de l'application. Gère la redirection par défaut vers le tableau de bord
 * ou l'affichage de la page de présentation (Landing Page).
 */
require_once __DIR__ . '/Controller.php';

class HomeController extends Controller {
    
    /**
     * Page d'accueil de l'application
     * Si l'utilisateur est connecté, le redirige vers son tableau de bord respectif (Admin ou Utilisateur).
     * Sinon, affiche la page de présentation.
     */
    public function index() {
        if (isset($_SESSION['user_id'])) {
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                $this->redirect('/admin');
            } else {
                $this->redirect('/dashboard');
            }
        } else {
            $this->view('home');
        }
    }

    /**
     * Page d'erreur de maintenance
     * Affiche une vue dédiée lorsque le système est inaccessible pour des raisons de mise à jour.
     */
    public function maintenance() {
        $this->view('errors/maintenance');
    }
}
