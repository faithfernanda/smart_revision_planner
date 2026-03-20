<?php
/**
 * Script de migration pour l'authentification Gmail (OTP)
 * Ce script ajoute les colonnes nécessaires à la table 'users'.
 */
require_once __DIR__ . '/config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    $sql = "ALTER TABLE users 
            ADD COLUMN IF NOT EXISTS reset_code VARCHAR(10),
            ADD COLUMN IF NOT EXISTS reset_expires_at TIMESTAMP,
            ADD COLUMN IF NOT EXISTS verification_code VARCHAR(10),
            ADD COLUMN IF NOT EXISTS is_verified BOOLEAN DEFAULT FALSE";

    $conn->exec($sql);
    echo "Migration réussie : Les colonnes d'authentification ont été ajoutées.\n";
} catch (PDOException $e) {
    echo "Erreur de migration : " . $e->getMessage() . "\n";
    echo "Veuillez exécuter le SQL manuellement dans pgAdmin :\n";
    echo "ALTER TABLE users ADD COLUMN reset_code VARCHAR(10), ADD COLUMN reset_expires_at TIMESTAMP, ADD COLUMN verification_code VARCHAR(10), ADD COLUMN is_verified BOOLEAN DEFAULT FALSE;\n";
}
?>
