<?php

class Utilisateur {

    public static function existe(PDO $pdo, $login) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
        $stmt->execute([$login]);
        return $stmt->fetch();
    }

    public static function creer(PDO $pdo, $login, $motdepasse) {
        $hash = password_hash($motdepasse, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (login, mot_de_passe, role) VALUES (?, ?, 'utilisateur')");
        return $stmt->execute([$login, $hash]);
    }

    public static function verifierConnexion(PDO $pdo, $login, $motdepasse) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
        $stmt->execute([$login]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur && password_verify($motdepasse, $utilisateur['mot_de_passe'])) {
            return $utilisateur;
        }
        return false;
    }
}