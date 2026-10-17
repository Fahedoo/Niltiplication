<?php
if (isset($_GET['ajax_search'])) {
    // NE PAS require_once ici, tu es déjà dans le bon fichier !
    $search = isset($_GET['q']) ? trim($_GET['q']) : '';
    $page = new Accueil();
    $page->loadArticles($search);
    foreach ($page->articles as $article) {
        echo "<a href='?article=" . htmlspecialchars($article['nom_article']) . "'>
                <div class='article'>
                    <article>
                        <img src='" . htmlspecialchars($article['thumbnail']) . "'>
                        <h4>" . htmlspecialchars($article['nom_article']) . "</h4>
                        <p>" . nl2br(htmlspecialchars($article['description'])) . "</p>
                    </article>
                </div>
            </a>";
    }
    exit;
}

Class Accueil extends Page
{
    public $articles = [];

    public function __construct()
    { 
        parent::__construct();
        if (
            isset($_SESSION['is_admin']) && $_SESSION['is_admin'] &&
            isset($_POST['add_article']) &&
            !empty($_POST['nom_article']) &&
            !empty($_POST['description']) &&
            !empty($_POST['thumbnail']) &&
            !empty($_POST['categorie']) // <-- Ajoute cette ligne
        ) {
            $sql = "INSERT INTO article (nom_article, description, thumbnail, categorie) VALUES (:nom, :desc, :thumb, :cat)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nom' => $_POST['nom_article'],
                'desc' => $_POST['description'],
                'thumb' => $_POST['thumbnail'],
                'cat' => $_POST['categorie'] // <-- Ajoute cette ligne
            ]);
        }
        $this->loadArticles();
    }

    // Recherche sur nom_article OU description
    public function loadArticles($search = '')
    {
        if (!empty($search)) {
            $sql = "SELECT * FROM article WHERE nom_article LIKE :search OR description LIKE :search";
            $stmt = $this->pdo->prepare($sql);
            $like = '%' . $search . '%';
            $stmt->bindParam(':search', $like, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            $sql = "SELECT * FROM article";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        }
        $this->articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function __toString()
    {   
        $addForm = "";
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            $addForm = '
            <form method="post" enctype="multipart/form-data" id="add-article-form" style="margin-bottom:32px;">
                <h3>Ajouter un article</h3>
                <input type="text" name="nom_article" placeholder="Nom de l\'article" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <input type="text" name="thumbnail" placeholder="URL de la miniature" required>
                <input type="text" name="categorie" placeholder="Catégorie" required>
                <button type="submit" name="add_article">Publier</button>
            </form>';
        }

        $content = "";
        foreach ($this->articles as $article) {
            $content .= "<a href='?article=" . htmlspecialchars($article['nom_article']) . "'>
                            <div class='article'>
                                <article>
                                    <img src='" . htmlspecialchars($article['thumbnail']) . "'>
                                    <h4>" . htmlspecialchars($article['nom_article']) . "</h4>
                                    <p>" . nl2br(htmlspecialchars($article['description'])) . "</p>
                                </article>
                            </div>
                        </a>";
        }

        // Barre de recherche seule
        $searchBar = "<input type='text' id='search' placeholder='Rechercher...' style='width:100%;max-width:500px;margin:0 auto 32px auto;display:block;'>";

        // Liste des articles dans une grille
        $articlesList = "<div id='articles-list' class='articles-grid'>$content</div>";

        $this->main .= "<main id='accueil'>
                        $addForm
                        $searchBar
                        $articlesList
                        </main>";

        return $this->debut . $this->main . $this->fin ;
    }
}
?>