<?php
/**
 * Service d'envoi d'emails (MailService)
 * Implémente un client SMTP léger pour Gmail sans dépendances externes.
 */

class MailService {
    private $config;

    public function __construct() {
        $this->config = require __DIR__ . '/../config/mail.php';
    }

    /**
     * Envoie un email via SMTP (Gmail SSL Port 465)
     */
    public function send($to, $subject, $body) {
        $host = $this->config['smtp_host'];
        $port = 465; // Utilisation forcée du port 465 (SSL) pour la fiabilité
        $user = $this->config['smtp_user'];
        $pass = $this->config['smtp_pass'];
        $from = $this->config['from_email'];
        $name = $this->config['from_name'];

        try {
            // Connexion sécurisée
            $socket = fsockopen("ssl://$host", $port, $errno, $errstr, 15);
            if (!$socket) {
                error_log("SMTP Connection Error: $errstr ($errno)");
                return false;
            }

            $this->getResponse($socket, "220");

            // Salutation (EHLO)
            $this->sendCommand($socket, "EHLO localhost", "250");

            // Authentification
            $this->sendCommand($socket, "AUTH LOGIN", "334");
            $this->sendCommand($socket, base64_encode($user), "334");
            $this->sendCommand($socket, base64_encode($pass), "235");

            // Expéditeur et Destinataire
            $this->sendCommand($socket, "MAIL FROM: <$user>", "250");
            $this->sendCommand($socket, "RCPT TO: <$to>", "250");

            // Envoi des données (DATA)
            $this->sendCommand($socket, "DATA", "354");

            // Construction de l'email
            $headers = "Subject: $subject\r\n";
            $headers .= "To: $to\r\n";
            $headers .= "From: $name <$user>\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            
            $emailData = $headers . "\r\n" . $body . "\r\n.\r\n";
            
            $this->sendCommand($socket, $emailData, "250");

            // Fermeture
            $this->sendCommand($socket, "QUIT", "221");
            fclose($socket);

            return true;
        } catch (Exception $e) {
            error_log("MailService Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoie une commande et vérifie le code de réponse
     */
    private function sendCommand($socket, $command, $expectedCode) {
        fwrite($socket, $command . "\r\n");
        return $this->getResponse($socket, $expectedCode);
    }

    /**
     * Lit la réponse du serveur
     */
    private function getResponse($socket, $expectedCode) {
        $response = "";
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == " ") break;
        }
        if (substr($response, 0, 3) !== $expectedCode) {
            throw new Exception("SMTP Error: Expected $expectedCode, got " . substr($response, 0, 3) . " ($response)");
        }
        return $response;
    }
}
?>
