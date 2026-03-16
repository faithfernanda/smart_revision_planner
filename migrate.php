<?php
// migrate.php
require_once __DIR__ . '/config/database.php';

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    die("Erreur de connexion à la base de données.");
}

echo "Début de la migration...\n";

try {
    // 1. Ajouter la colonne session_id si elle n'existe pas
    // Note: Pour PostgreSQL, on vérifie d'abord si elle existe
    $checkSql = "SELECT column_name FROM information_schema.columns WHERE table_name='notifications' AND column_name='session_id'";
    $stmt = $conn->query($checkSql);
    $exists = $stmt->fetch();

    if (!$exists) {
        echo "Ajout de la colonne session_id...\n";
        $alterSql = "ALTER TABLE notifications ADD COLUMN session_id INTEGER REFERENCES revision_sessions(id) ON DELETE SET NULL";
        $conn->exec($alterSql);
        echo "Colonne session_id ajoutée avec succès.\n";
    } else {
        echo "La colonne session_id existe déjà.\n";
    }

    // 2. Ajouter la colonne subject_id pour plus de flexibilité (optionnel mais recommandé par le schéma fixé)
    $checkSql = "SELECT column_name FROM information_schema.columns WHERE table_name='notifications' AND column_name='subject_id'";
    $stmt = $conn->query($checkSql);
    $exists = $stmt->fetch();

    if (!$exists) {
        echo "Ajout de la colonne subject_id...\n";
        $alterSql = "ALTER TABLE notifications ADD COLUMN subject_id INTEGER REFERENCES subjects(id) ON DELETE SET NULL";
        $conn->exec($alterSql);
        echo "Colonne subject_id ajoutée avec succès.\n";
    }

    echo "Migration terminée avec succès ! 🚀\n";

} catch (PDOException $e) {
    echo "Erreur lors de la migration : " . $e->getMessage() . "\n";
}
