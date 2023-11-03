<?php
// register.php
session_start();
// Verbindung zur Datenbank herstellen
$db = new PDO("mysql:host=localhost;dbname=bro", "root", "");
// Benutzername, Passwort und E-Mail aus dem Formular erhalten
$username = $_POST["username"];
$password = $_POST["password"];
$email = $_POST["email"];
// Überprüfen, ob der Benutzername bereits in der Datenbank vorhanden ist
$stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(["username" => $username]);
$user = $stmt->fetch();
if ($user) {
    // Benutzername bereits vergeben
    echo "Benutzername bereits vergeben";
} else {
    // Passwort mit einem sicheren Algorithmus hashen
    $hash = password_hash($password, PASSWORD_DEFAULT);
    // Benutzer in die Datenbank einfügen
    $stmt = $db->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
    $stmt->execute(["username" => $username, "password" => $hash, "email" => $email]);
    // Benutzer in die Session speichern
    $_SESSION["user"] = ["username" => $username, "password" => $hash, "email" => $email];
    // Zur Home-Seite weiterleiten
    header("Location: home.html");
}
?>
