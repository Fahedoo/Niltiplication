<?php
if (isset($_GET["categorie"])){
    define("TITRE",$_GET["categorie"]." - Niltiplication");
} else {
    define("TITRE","Niltiplication");
}

?>