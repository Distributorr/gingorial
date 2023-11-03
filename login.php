<?php
// login.php
session_start();
// Verbindung zur Datenbank herstellen
$db = new PDO("mysql:host=localhost;dbname=bro", "root", "");
// Benutzername und Passwort aus dem Formular erhalten
$username = $_POST["username"];
$password = $_POST["password"];
// Überprüfen, ob der Benutzername und das Passwort in der Datenbank vorhanden sind
$stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(["username" => $username]);
$user = $stmt->fetch();
if ($user) {
    // Passwort mit dem gespeicherten Hash vergleichen
    if (password_verify($password, $user["password"])) {
        // Benutzer in die Session speichern
        $_SESSION["user"] = $user;
        // Zur Home-Seite weiterleiten
        header("Location: home.html");
    } else {
        // Falsches Passwort
        echo "Falsches Passwort";
    }
} else {
    // Benutzername nicht gefunden
    echo "Benutzername nicht gefunden";
}
?>
