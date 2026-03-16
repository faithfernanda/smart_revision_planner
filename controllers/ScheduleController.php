<?php
/**
 * Contrôleur du Planning (Calendrier)
 * Gère l'affichage du calendrier, la récupération des événements, et la modification des séances de révision.
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/RevisionSession.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class ScheduleController extends Controller {
    
    /**
     * Affiche la vue principale du calendrier
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $user_id = $this->getTargetUserId();
        $is_admin_view = (User::isAdmin() && isset($_GET['user_id']));
        
        $user_name = $_SESSION['user_name'];
        if ($user_id != $_SESSION['user_id']) {
            $userModel = new User();
            $targetUser = $userModel->getById($user_id);
            if ($targetUser) {
                $user_name = $targetUser['firstname'] . ' ' . $targetUser['lastname'];
            }
        }

        $this->view('schedule/index', [
            'user_name' => $user_name,
            'is_admin_view' => $is_admin_view
        ]);
    }

    /**
     * Retourne les sessions de révision au format JSON pour FullCalendar
     */
    public function events() {
        if (!isset($_SESSION['user_id'])) {
            $this->json([]);
        }

        $user_id = $this->getTargetUserId();
        
        $revisionModel = new RevisionSession();
        $sessions = $revisionModel->getAll($user_id);

        $events = [];
        foreach ($sessions as $session) {
            $events[] = [
                'id' => $session['id'],
                'title' => $session['subject_name'],
                'start' => $session['start_datetime'],
                'end' => $session['end_datetime'],
                'color' => $session['color'], // Couleur récupérée depuis la matière associée
                'textColor' => '#ffffff', // Texte blanc pour assurer le contraste
                'extendedProps' => [
                    'status' => $session['status'] // État : 'upcoming' ou 'completed'
                ]
            ];
        }

        $this->json($events);
    }

    /**
     * Marque une séance comme terminée via appel AJAX
     */
    public function complete() {
        if (!isset($_SESSION['user_id']) || User::isAdmin()) {
            $this->json(['success' => false]);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false]);
            return;
        }

        $this->verifyCsrf();
        $input = $this->getJsonInput();
        $sessionId = $input['id'] ?? null;

        if ($sessionId) {
            $revisionModel = new RevisionSession();
            // Met à jour le statut en base de données
            $success = $revisionModel->updateStatus($sessionId, 'completed', $_SESSION['user_id']);
            $this->json(['success' => $success]);
        } else {
            $this->json(['success' => false]);
        }
    }

    /**
     * Génère une vue optimisée pour l'impression du planning
     */
    /**
     * Génère une vue optimisée pour l'impression du planning
     */
    public function print() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $user_id = $this->getTargetUserId();
        $user_name = $_SESSION['user_name'];
        if ($user_id != $_SESSION['user_id']) {
            $userModel = new User();
            $targetUser = $userModel->getById($user_id);
            if ($targetUser) {
                $user_name = $targetUser['firstname'] . ' ' . $targetUser['lastname'];
            }
        }

        $revisionModel = new RevisionSession();
        $sessions = $revisionModel->getUpcoming($user_id); // Récupère les révisions prévues

        $this->view('schedule/print', [
            'user_name' => $user_name,
            'sessions' => $sessions
        ]);
    }

    /**
     * Met à jour les horaires d'une séance (drag & drop ou redimensionnement sur le calendrier)
     */
    public function update() {
        if (!isset($_SESSION['user_id']) || User::isAdmin()) {
            $this->json(['success' => false, 'message' => 'Non autorisé']);
            return;
        }

        $this->verifyCsrf();
        $input = $this->getJsonInput();
        $id = $input['id'] ?? null;
        $start = $input['start'] ?? null;
        $end = $input['end'] ?? null;

        if ($id && $start && $end) {
            $revisionModel = new RevisionSession();
            $id = (int)$id;
            $session = $revisionModel->getById($id, $_SESSION['user_id']);
            
            if ($session) {
                try {
                    $success = $revisionModel->update($id, ['start_datetime' => $start, 'end_datetime' => $end], $_SESSION['user_id']);
                    
                    if ($success) {
                        // Journalisation et notification des administrateurs
                        $log = new ActivityLog();
                        $log->log($_SESSION['user_id'], 'SCHEDULE_UPDATED', "Séance '{$session['subject_name']}' modifiée");

                        require_once __DIR__ . '/../models/Notification.php';
                        $notificationModel = new Notification();
                        $userModel = new User();
                        $admins = $userModel->getAdmins();
                        foreach ($admins as $admin) {
                            $notificationModel->create($admin['id'], "📝 Séance Modifiée", "{$_SESSION['user_name']} a modifié une séance ({$session['subject_name']}).");
                        }
                    }
                    $this->json(['success' => $success, 'message' => $success ? '' : 'Échec de la mise à jour en base']);
                } catch (Exception $e) {
                    $this->json(['success' => false, 'message' => 'Erreur DB: ' . $e->getMessage()]);
                }
            } else {
                $this->json(['success' => false, 'message' => 'Session introuvable (ID: ' . $id . ')']);
            }
        } else {
            $this->json(['success' => false, 'message' => 'Données manquantes (ID: ' . ($id ?? 'null') . ')']);
        }
    }

    /**
     * Supprime une séance de révision
     */
    public function delete() {
        if (!isset($_SESSION['user_id']) || User::isAdmin()) {
            $this->json(['success' => false, 'message' => 'Non autorisé']);
            return;
        }

        $this->verifyCsrf();
        $input = $this->getJsonInput();
        $id = $input['id'] ?? null;

        if ($id) {
            $revisionModel = new RevisionSession();
            $id = (int)$id;
            $session = $revisionModel->getById($id, $_SESSION['user_id']);

            if ($session) {
                try {
                    $success = $revisionModel->delete($id, $_SESSION['user_id']);
                    
                    if ($success) {
                        // Journalisation et notification
                        $log = new ActivityLog();
                        $log->log($_SESSION['user_id'], 'SCHEDULE_DELETED', "Séance '{$session['subject_name']}' supprimée");

                        require_once __DIR__ . '/../models/Notification.php';
                        $notificationModel = new Notification();
                        $userModel = new User();
                        $admins = $userModel->getAdmins();
                        foreach ($admins as $admin) {
                            $notificationModel->create($admin['id'], "🗑️ Séance Supprimée", "{$_SESSION['user_name']} a supprimé une séance ({$session['subject_name']}).");
                        }
                    }
                    $this->json(['success' => $success, 'message' => $success ? '' : 'Échec de la suppression en base']);
                } catch (Exception $e) {
                    $this->json(['success' => false, 'message' => 'Erreur DB: ' . $e->getMessage()]);
                }
            } else {
                $this->json(['success' => false, 'message' => 'Session introuvable (ID: ' . $id . ')']);
            }
        } else {
            $this->json(['success' => false, 'message' => 'ID manquant']);
        }
    }

    /**
     * Vérifie les révisions imminentes
     * Utilisé pour déclencher des alertes push/système avant le début d'une séance.
     */
    public function checkUpcoming() {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['success' => false]);
            return;
        }

        $revisionModel = new RevisionSession();
        $nextSession = $revisionModel->getNextSoon($_SESSION['user_id'], 15); // Vérifie les séances débutant dans les 15 prochaines minutes

        require_once __DIR__ . '/../models/Notification.php';
        $notificationModel = new Notification();

        if ($nextSession) {
            $start = new DateTime($nextSession['start_datetime']);
            $now = new DateTime();
            $diff = $now->diff($start);
            $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;

            // Empêche les notifications en doublon pour la même séance
            if (!$notificationModel->existsForSession($_SESSION['user_id'], $nextSession['id'])) {
                $msg = "Révision : " . $nextSession['subject_name'] . " commence à " . $start->format('H:i');
                $notificationModel->create($_SESSION['user_id'], "📚 Prochaine Séance", $msg, $nextSession['id']);
            }

            $this->json([
                'success' => true,
                'session' => [
                    'id' => $nextSession['id'],
                    'subject' => $nextSession['subject_name'],
                    'time' => $start->format('H:i'),
                    'minutes' => $minutes
                ],
                'unread_count' => $notificationModel->getUnreadCount($_SESSION['user_id'])
            ]);
        } else {
            $this->json([
                'success' => false,
                'unread_count' => $notificationModel->getUnreadCount($_SESSION['user_id'])
            ]);
        }
    }
}
