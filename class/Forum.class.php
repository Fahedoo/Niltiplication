<?php
Class Forum extends Page
{
    private $message;

    public function __toString()
    {
        parent::__construct();

        // Traitement du formulaire
        if (isset($_POST['publier'])) {
            $this->ajouterPost();
        }
        $sql = "SELECT publication.*, utilisateur.pseudo AS auteur, utilisateur.photo_profil FROM publication JOIN utilisateur ON publication.auteur = utilisateur.pseudo ORDER BY publication.date_publication DESC";
        $query = $this->pdo->query($sql);
        $posts = $query->fetchAll(PDO::FETCH_ASSOC);
            
        $this->main .= "<main id='discussion'>";
        // Formulaire pour publier un message
        if (isSet($_SESSION["user"])){
            $this->main .= "<h3>Bonjour ".$_SESSION["user"]." !</h3>".$_SESSION["forum_message"]."
            <form method='post' action=''>
            <textarea name='contenu' placeholder='Votre message' required></textarea>
            <button type='submit' name='publier'>Publier</button>
            </form>
        ";
        } else {
            $this->main .= "
            <p>Vous devez être connecté pour discuter. <a href='?categorie=Login&action=login'><strong>Se connecter</strong></a></p>
            ";
        }
        
        $this->main .= "<h2>Discussion</h2>";
        foreach ($posts as $post) {
            $photo = (!empty($post['photo_profil']) && strtolower($post['photo_profil']) !== 'null') ? htmlspecialchars($post['photo_profil']) : 'default.jpg';

            $this->main .= "<div class='message'>";
            $this->main .= "<img src='img/profil/" . $photo . "' alt='Photo de profil' class='photo-profil'>";
            $this->main .= "<div>";
            $this->main .= "<p><strong>" . htmlspecialchars($post['auteur']) . " : </strong>" . htmlspecialchars($post['message']) . "</p>";
            $this->main .= "<small>Posté le : " . htmlspecialchars($post['date_publication']) . "</small>";
            $this->main .= "</div>";
            $this->main .= "</div><hr>";
        }
        $this->main .= "</main>";
            
        
        return $this->debut . $this->main . $this->fin;
    }

    public function ajouterPost()
    {
        if (isset($_SESSION['user'])) {
            try {
                $stmt = $this->pdo->prepare("INSERT INTO publication (auteur, date_publication, message) VALUES (:pseudo, NOW(), :message)");
                $stmt->bindParam(':pseudo', $_SESSION["user"]);
                $stmt->bindParam(':message', $_POST["contenu"]);
                $stmt->execute();
                $_SESSION['forum_message'] = "<p style='color:green'>Message publié !</p>";
                header("Location: " . $_SERVER['REQUEST_URI']);
            } catch (PDOException $e) {
                $this->message = "Erreur lors de l'ajout du post : " . $e->getMessage();
            }
            
        }
    }
}
?>