<?php
session_start();

// Chargement automatique des classes avec .class.php
spl_autoload_register(function ($class) {
    $fichier = __DIR__ . '/class/' . $class . '.class.php';
    if (file_exists($fichier)) {
        require_once $fichier;
    }
});

// Connexion à la base (prévu pour demain)
// require_once __DIR__ . '/inc/connexion.php';

// Récupération de la page demandée
$page = isset($_GET['page']) ? $_GET['page'] : 'accueil';



// Routeur principal
switch ($page) {
    case 'accueil':
        $vue = new VueAccueil();
        $vue->afficher();
        break;

    case 'article':
        $vue = new VueArticle();
        $vue->afficher();
        break;

    case 'mentions':
        $vue = new VueMentions();
        $vue->afficher();
        break;

    case 'apropos':
        $vue = new VueApropos();
        $vue->afficher();
        break;

    case 'quiz':
        $vue = new VueQuizz();
        $vue->afficher();
        break;
       
  case 'login':
    require_once("class/Utilisateur.class.php");
    require_once("inc/connexion.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $user = Utilisateur::verifierConnexion($pdo, $login, $password);

        if ($user) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php?page=accueil");
            exit;
        } else {
            echo "<p style='text-align:center; color:red;'>Identifiants incorrects</p>";
        }
    }

    $vue = new VueLogin();
    $vue->afficher();
    break;

case 'inscription':
    require_once("class/Utilisateur.class.php");
    require_once("inc/connexion.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        if ($password !== $confirm) {
            echo "<p style='text-align:center; color:red;'>Les mots de passe ne correspondent pas.</p>";
        } elseif (Utilisateur::existe($pdo, $login)) {
            echo "<p style='text-align:center; color:red;'>Ce login existe déjà.</p>";
        } else {
            Utilisateur::creer($pdo, $login, $password);
            echo "<p style='text-align:center; color:green;'>Compte créé avec succès. <a href='index.php?page=login'>Se connecter</a></p>";
        }
    }

    $vue = new VueInscription();
    $vue->afficher();
    break;


    case 'logout':
        session_destroy();
        header("Location: index.php?page=accueil");
        exit;

    default:
        echo "<h1 style='text-align:center;'>Page introuvable</h1>";
        break;

}


