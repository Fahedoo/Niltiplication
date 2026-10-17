<?php
Class Login extends Page
{
    private $message = "";

    function __construct()
    {
        parent::__construct();
        $this->traiterFormulaire();
    }

    public function __toString()
    {
        if ($_GET["action"]=="login")
        {
            $this->main = "<main id='formulaire'>
                        <form method='post' action=''>
                            <h2>Connexion</h2>
                            {$this->message}
                            <label for='login'>Identifiant :</label>
                            <input type='text' id='login' name='login' required><br>
                            <label for='password'>Mot de passe :</label>
                            <input type='password' id='password' name='password' required><br>
                            <button type='submit' name='action' value='connexion'>Se connecter</button>
                            <p>Pas encore de compte ? <a href='?categorie=Login&action=inscription'>Inscrivez-vous</a></p>
                        </form>
                       </main>";
        } else {
            $this->main = "<main id='formulaire'>
                     <form method='post' action=''>
                        <h2>Inscription</h2>
                        {$this->message}
                        <label for='pseudo'>Pseudo :</label>
                        <input type='text' id='pseudo' name='pseudo' required><br>
                        <label for='mail'>Adresse mail :</label>
                        <input type='mail' id='mail' name='mail' required><br>
                        <label for='password'>Mot de passe :</label>
                        <input type='password' id='password' name='password' required><br>
                        <button type='submit' name='action' value='inscription'>S'inscrire</button>
                        <p>Déjà un compte ? <a href='?categorie=Login&action=login'>Connectez-vous</a></p>
                     </form>
                    </main>";
        }
        return $this->debut . $this->main . $this->fin;
                
    }

    function traiterFormulaire() 
    {
        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            // Déconnexion
            session_unset();
            session_destroy();
            header('Location: ?categorie=Login&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && !isset($_SESSION['user'])) { 
            if ($_POST['action'] === 'connexion') {
                $login = $_POST['login'];
                $password = $_POST['password'];
                $sql = "SELECT * FROM utilisateur WHERE pseudo = :login OR mail = :login";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['login' => $login]);
                $user = $stmt->fetch(); //tableau dans $user
                if ($user && $password == $user['password']) {
                    // Connexion réussie
                    $_SESSION['user'] = $user['pseudo'];
                    $_SESSION['is_admin'] = $user['admin'];
                    header('Location: index.php');
                    exit();
                } else {
                    // Erreur de connexion
                    $this->message .= "<p>Identifiants incorrects.</p>";
                }
            } elseif ($_POST['action'] === 'inscription') {
                $pseudo = $_POST['pseudo'];
                $mail = $_POST['mail'];
                $password = $_POST['password']; //chiffre le password
                // Vérifier si l'utilisateur existe déjà
                $sql = "SELECT * FROM utilisateur WHERE pseudo = :pseudo OR mail = :mail";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['pseudo' => $pseudo, 'mail' => $mail]);
                $fetch = $stmt->fetch();
                if ($fetch) {
                    $this->message .= "<p>Pseudo ou mail déjà utilisé.</p>";
                } else {
                    $sql = "INSERT INTO utilisateur (pseudo, mail, password) VALUES (:pseudo, :mail, :password)";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute(['pseudo' => $pseudo, 'mail' => $mail, 'password' => $password]);
                    $this->message .= "<p>Inscription réussie. Vous pouvez vous <a href='?categorie=Login&action=login'>connecter.</a></p>";
                }
            }
        }
    }
}
?>