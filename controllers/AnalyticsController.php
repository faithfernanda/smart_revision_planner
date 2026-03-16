<?php
/**
 * Contrôleur d'Analytique
 * Gère l'affichage des statistiques de révision, le taux de complétion et les recommandations personnalisées.
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/RevisionSession.php';
require_once __DIR__ . '/../models/Exam.php';

class AnalyticsController extends Controller {
    
    /**
     * Page principale des analyses
     * Calcule les données pour les graphiques et génère des conseils basés sur la progression.
     */
    public function index() {
        // Redirige vers la connexion si l'utilisateur n'est pas identifié
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        // Détermine l'ID de l'utilisateur cible (soi-même ou un tiers si admin)
        $user_id = $this->getTargetUserId();
        $is_admin_view = (User::isAdmin() && isset($_GET['user_id']));
        
        $user_name = $_SESSION['user_name'];
        // Si visionnage par un admin, récupère le nom de l'étudiant concerné
        if ($user_id != $_SESSION['user_id']) {
            $userModel = new User();
            $targetUser = $userModel->getById($user_id);
            if ($targetUser) {
                $user_name = $targetUser['firstname'] . ' ' . $targetUser['lastname'];
            }
        }

        $revisionModel = new RevisionSession();
        $examModel = new Exam();

        // Récupération des données brutes depuis les modèles
        $subjectStats = $revisionModel->getSubjectStats($user_id); // Heures par matière
        $completionStats = $revisionModel->getCompletionStats($user_id); // États de complétion (terminé, en attente, etc.)
        $subjectCompletion = $revisionModel->getSubjectCompletionStats($user_id); // Taux de préparation par matière
        $exams = $examModel->getUpcoming($user_id); // Examens à venir

        // Préparation des données pour les graphiques (formatage pour Chart.js)
        $labels = [];
        $dataHours = [];
        $colors = [];

        foreach ($subjectStats as $stat) {
            $labels[] = $stat['name'];
            $dataHours[] = round($stat['total_hours'], 1);
            $colors[] = $stat['color'];
        }

        // Calcul du taux global de complétion
        $totalSessions = array_sum($completionStats);
        $completedSessions = $completionStats['completed'] ?? 0;
        $completionRate = $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100) : 0;

        // Logique de génération des recommandations personnalisées
        $recommendations = [];
        $now = new DateTime();
        
        foreach ($subjectCompletion as $stat) {
            // Associe chaque matière à son examen correspondant
            foreach ($exams as $exam) {
                if ($exam['subject_id'] == $stat['id']) {
                    $examDate = new DateTime($exam['exam_date']);
                    $daysUntil = $now->diff($examDate)->days;
                    
                    // Alerte critique si l'examen est proche et la préparation insuffisante
                    if ($stat['completion_rate'] < 50 && $daysUntil < 7) {
                        $recommendations[] = [
                            'type' => 'critical',
                            'message' => "Urgence sur <strong>{$stat['name']}</strong> : seulement {$stat['completion_rate']}% de préparation pour un examen dans {$daysUntil} jours !",
                            'icon' => 'alert-triangle'
                        ];
                    } 
                    // Avertissement si le taux de complétion est trop bas
                    elseif ($stat['completion_rate'] < 30) {
                        $recommendations[] = [
                            'type' => 'warning',
                            'message' => "Vous prenez du retard sur <strong>{$stat['name']}</strong>. Essayez de bloquer une session aujourd'hui.",
                            'icon' => 'clock'
                        ];
                    }
                }
            }
        }

        // Messages d'encouragement par défaut si aucune alerte n'est déclenchée
        if (empty($recommendations)) {
            if ($completionRate > 80) {
                $recommendations[] = [
                    'type' => 'success',
                    'message' => "Excellent travail ! Continuez sur cette lancée pour maintenir votre rythme.",
                    'icon' => 'crown'
                ];
            } else {
                $recommendations[] = [
                    'type' => 'info',
                    'message' => "Conseil : Utilisez le timer Pomodoro pour rester concentré pendant vos séances longues.",
                    'icon' => 'lightbulb'
                ];
            }
        }

        // Rendu de la vue analytique avec toutes les données formatées
        $this->view('analytics/index', [
            'user_name' => $user_name,
            'is_admin_view' => $is_admin_view,
            'chartLabels' => json_encode($labels),
            'chartData' => json_encode($dataHours),
            'chartColors' => json_encode($colors),
            'completionRate' => $completionRate,
            'totalSessions' => $totalSessions,
            'subjectCompletion' => $subjectCompletion,
            'recommendations' => $recommendations,
            'exams' => $exams
        ]);
    }
}
