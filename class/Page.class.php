<?php
Class Page
{
    const DB = "a2425_ismailialaoui_SAE203";
    const HOST = "localhost";
    const USER = "ismailialaoui";
    const PASS = "aec7640";
    const charset = "utf8mb4";
    const DSN = "mysql:host=".self::HOST.";dbname=".self::DB.";charset=".self::charset;
    

    protected $main;
    

    function __construct()
    {
        try {
            $this->pdo = new PDO(self::DSN, self::USER, self::PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
        $this->debut = "<!DOCTYPE html>
            <html lang='fr' xml:lang='fr' xmlns='http://www.w3.org/1999/xhtml'>

            <head>
                <meta charset='UTF-8'/>
                <meta name='viewport' content='width=device-width, initial-scale=1'/>
                <title>";
        if (isset($_GET["categorie"])){
                        $this->debut .= $_GET["categorie"] ." - Niltiplication";
                    } else {
                        $this->debut .= "Niltiplication";
                    }
        $this->debut .= "</title>
                <link rel='stylesheet' href='./css/style_niltiplication.css' />
                <link href='https://fonts.googleapis.com/css2?family=Inknut+Antiqua:wght@400;700&display=swap' rel='stylesheet'>
                <script src='js/script.js'></script>
            </head>
            <body>
                <div id='Cartouche'>
                    <header>
                        <h1 id='Logo_Niltiplication'>Niltiplication</h1>
                    </header>
                    <nav>
                        <a href='/~ismailialaoui/SAE_203/'>Explorer</a>
                        <a href='?categorie=Forum'>Forum</a>";
        if (!isset($_SESSION["user"])) {
            $this->debut .= "<a href='?categorie=Login&action=login' id='interraction_user'>Se connecter</a>";
        } else {
            $this->debut .= "<a href='?categorie=User' id='interraction_user'>".$_SESSION["user"]."</a><a href='?categorie=Login&action=logout' id='interraction_user'>Se déconnecter</a></div>";
        }
        $this->debut .= "
                    </nav>
                </div>";
        
        $this->fin = "<footer>
                        <p>Copyright - Tous droits réservés - Fahed ISMAILI ALAOUI</p>
                        <a href='?categorie=Mentions_légales'>Mentions légales</a>
                    </footer>
                  </body>
                  </html>";

    }

    function __toString()
    {
        $this->main = null;
        return $this->debut . $this->main . $this->fin;
        
    }
}
?>