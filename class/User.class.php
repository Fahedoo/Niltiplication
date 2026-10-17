<?php
Class User extends Page
{
    function __construct()
    {
        parent::__construct();
        $this->utilisateur = $_SESSION['user'];
        $sql = "SELECT * FROM utilisateur WHERE pseudo = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam('id', $_SESSION['user'], PDO::PARAM_STR);
        $stmt->execute();
        $this->utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->profilUpdate();
    }

    function __toString()
    {
        $this->main = "<main id='user_page'>
                        <h2>Profil de l'utilisateur</h2>";
        $this->main .= "<p><strong>Pseudo :</strong> " . htmlspecialchars($this->utilisateur['pseudo']) . "</p>";
        $this->main .= "<p><strong>Email :</strong> " . htmlspecialchars($this->utilisateur['mail']) . "</p>";
        $this->main .= "<img src='img/profil/" . htmlspecialchars($this->utilisateur['photo_profil']) . "' alt='Photo de profil' class='photo-profil';'><br><br>";

        $this->main .= '<form method="post" enctype="multipart/form-data">
            <label for="pseudo">Nouveau pseudo :</label>
            <input type="text" name="pseudo" id="pseudo" value="' . htmlspecialchars($this->utilisateur['pseudo']) . '"><br>
            <label for="photo">Nouvelle photo de profil :</label>
            <input type="file" name="photo" id="photo"><br>
            <input type="submit" name="update_profile" value="Mettre à jour">
        </form></main>';

        return $this->debut.$this->main.$this->fin;
    }

    function profilUpdate()
    {
        if (isset($_POST['update_profile'])) {
            $updateFields = [];
            $params = [];

            // Mise à jour du pseudo si fourni et différent
            if (!empty($_POST['pseudo']) && $_POST['pseudo'] !== $this->utilisateur['pseudo']) {
                $updateFields[] = "pseudo = :pseudo";
                $params[':pseudo'] = $_POST['pseudo'];
            }

            // Mise à jour de la photo de profil si un fichier est uploadé
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['photo']['tmp_name'];
                $fileName = basename($_FILES['photo']['name']);
                $fileSize = $_FILES['photo']['size'];
                $fileType = mime_content_type($fileTmpPath);

                $allowedTypes = ['image/webp', 'image/jpeg', 'image/png', 'image/gif'];
                $maxSize = 30 * 1024; // 30KB

                if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newFileName = uniqid('pfp_', true) . '.' . $ext;
                    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/img/profil/';
                    $destPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $updateFields[] = "photo_profil = :photo";
                        $params[':photo'] = $newFileName;
                        $this->utilisateur['photo_profil'] = $newFileName;
                    }
                }
            }

            // Exécuter la requête de mise à jour si nécessaire
            if (!empty($updateFields)) {
                $sql = "UPDATE utilisateur SET " . implode(', ', $updateFields) . " WHERE pseudo = :oldpseudo";
                $stmt = $this->pdo->prepare($sql);
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                }
                $stmt->bindValue(':oldpseudo', $this->utilisateur['pseudo'], PDO::PARAM_STR);
                $stmt->execute();

                // Mettre à jour l'objet utilisateur et la session si le pseudo a changé
                if (isset($params[':pseudo'])) {
                    $_SESSION['user'] = $params[':pseudo'];
                    $this->utilisateur['pseudo'] = $params[':pseudo'];
                }
            }
        }
    }
}
?>