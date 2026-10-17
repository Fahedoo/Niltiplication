<?php

class Article {

    public static function getById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?"); 
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getAll($pdo) {
        $stmt = $pdo->query("SELECT * FROM articles ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public static function existe($pdo, $id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}

