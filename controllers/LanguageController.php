<?php
// controllers/LanguageController.php
require_once __DIR__ . '/../utils/helpers.php';

class LanguageController {
    public function switch() {
        if (isset($_GET['lang'])) {
            $lang = $_GET['lang'];
            if (in_array($lang, ['fr', 'en'])) {
                $_SESSION['lang'] = $lang;
            }
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/dashboard');
        header('Location: ' . $referer);
        exit;
    }
}
