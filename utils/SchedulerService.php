<?php
// utils/SchedulerService.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Availability.php';
require_once __DIR__ . '/../models/Exam.php';

class SchedulerService {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function generateSchedule($user_id) {
        // 1. Clear existing future planned sessions to avoid duplicates/overlap
        $this->clearFutureSessions($user_id);

        // 2. Fetch Data
        $availabilityModel = new Availability();
        $examModel = new Exam();
        
        $slots = $availabilityModel->getAll($user_id);
        $exams = $examModel->getUpcoming($user_id);

        if (empty($slots) || empty($exams)) {
            return false; // Cannot generate without slots or exams
        }

        // 3. Algorithm: Prioritize revisions
        // We will generate schedule for the next 4 weeks
        $startDate = new DateTime();
        $endDate = (new DateTime())->modify('+4 weeks');

        $sessionsToInsert = [];

        foreach ($exams as $exam) {
            $examDate = new DateTime($exam['exam_date']);
            $score = ($exam['difficulty_level'] * $exam['coefficient']); 
            // Simple heuristic: heavier subjects need more sessions
            
            $sessionsNeeded = ceil($score / 2); // e.g., Diff 8 * Coeff 2 = 16 -> 8 sessions
            $sessionsScheduled = 0;

            // Iterate days from now until exam
            $currentDay = clone $startDate;
            while ($currentDay < $examDate && $currentDay < $endDate && $sessionsScheduled < $sessionsNeeded) {
                $dayOfWeek = $currentDay->format('w'); // 0 (Sun) - 6 (Sat)
                
                // Find slots for this day
                foreach ($slots as $slot) {
                    if ($slot['day_of_week'] == $dayOfWeek) {
                        // Check if slot is already taken in our local list
                        $slotStart = new DateTime($currentDay->format('Y-m-d') . ' ' . $slot['start_time']);
                        $slotEnd = new DateTime($currentDay->format('Y-m-d') . ' ' . $slot['end_time']);
                        
                        if ($this->isSlotFree($sessionsToInsert, $slotStart, $slotEnd)) {
                            $sessionsToInsert[] = [
                                'user_id' => $user_id,
                                'subject_id' => $exam['subject_id'],
                                'start_datetime' => $slotStart->format('Y-m-d H:i:s'),
                                'end_datetime' => $slotEnd->format('Y-m-d H:i:s'),
                                'weight' => $score // Keep track to maybe optimize later
                            ];
                            $sessionsScheduled++;
                            if ($sessionsScheduled >= $sessionsNeeded) break;
                        }
                    }
                }
                $currentDay->modify('+1 day');
            }
        }

        // 4. Batch Insert
        $this->saveSessions($sessionsToInsert);
        return true;
    }

    private function isSlotFree($sessions, $start, $end) {
        foreach ($sessions as $s) {
            $sStart = new DateTime($s['start_datetime']);
            $sEnd = new DateTime($s['end_datetime']);

            // Check overlap
            if ($start < $sEnd && $end > $sStart) {
                return false;
            }
        }
        return true;
    }

    private function clearFutureSessions($user_id) {
        $query = "DELETE FROM revision_sessions WHERE user_id = :uid AND start_datetime > NOW() AND status = 'planned'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
    }

    private function saveSessions($sessions) {
        $query = "INSERT INTO revision_sessions (user_id, subject_id, start_datetime, end_datetime, status) VALUES (:uid, :sid, :start, :end, 'planned')";
        $stmt = $this->conn->prepare($query);

        foreach ($sessions as $session) {
            $stmt->bindParam(":uid", $session['user_id']);
            $stmt->bindParam(":sid", $session['subject_id']);
            $stmt->bindParam(":start", $session['start_datetime']);
            $stmt->bindParam(":end", $session['end_datetime']);
            $stmt->execute();
        }
    }
}
