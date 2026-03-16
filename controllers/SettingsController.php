<?php
/**
 * Contrôleur des Paramètres
 * Réservé aux administrateurs. Permet de modifier le mode maintenance et le nom du site.
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Setting.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class SettingsController extends Controller {
    
    /**
     * Constructeur
     * Vérifie les droits d'administration.
     */
    public function __construct() {
        parent::__construct();
        if (!User::isAdmin()) {
            $this->redirect('/dashboard');
        }
    }

    /**
     * Affiche la page des paramètres de configuration
     */
    public function index() {
        $settingModel = new Setting();
        
        // Récupération des valeurs actuelles
        $settings = [
            'maintenance_mode' => $settingModel->get('maintenance_mode', 'off'),
            'site_name' => $settingModel->get('site_name', 'Smart Revision'),
        ];
        
        $this->view('admin/settings', ['settings' => $settings]);
    }

    /**
     * Traite la mise à jour des paramètres globaux
     */
    public function update() {
        $this->verifyCsrf(); // Sécurité contre les requêtes frauduleuses
        
        $settingModel = new Setting();
        $log = new ActivityLog();
        
        $maintenance = $_POST['maintenance_mode'] ?? 'off';
        $siteName = $_POST['site_name'] ?? 'Smart Revision';
        
        // Enregistre les nouveaux réglages en base de données
        $settingModel->set('maintenance_mode', $maintenance);
        $settingModel->set('site_name', $siteName);
        
        // Journalisation du changement de configuration
        $log->log($_SESSION['user_id'], 'UPDATE_SETTINGS', "Maintenance: $maintenance, Nom: $siteName");
        
        $this->redirect('/admin/settings?msg=updated');
    }
}
