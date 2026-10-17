<?php
Class Mentions_legales extends Page
{
    function __construct()
    {
        parent::__construct();
        $this->main = "<main id='mentions'>
                <h3>Édition du site</h3>
                    <p>\"Niltiplication est un projet universitaire réalisé dans un cadre pédagogique.\"</p>
                    <p>Fahed ISMAILI ALAOUI</p>
                    <p>Étudiant en BUT MMI à l'IUT de Bobigny</p>
                    <p>fahedismaili32@gmail.com</p>

                <h3>Hébergeur</h3>
                    <p>Serveur de projets de l'IUT de Bobigny</p>
                    <p>1 Rue de Chablis, 93000 Bobigny</p>
                    <p>01 48 38 88 01</p>

                <h3>Propriété intellectuelle</h3>
                    <p>Les contenus présents sur ce site (textes, images, schémas, etc.) sont la propriété de leur auteur, sauf mention contraire.</p>
                    <p>Toute reproduction, même partielle, sans autorisation est interdite, sauf pour un usage strictement pédagogique ou privé avec citation de la source.</p>

                <h3>Données personnelles</h3>
                    <p>Le site ne collecte aucune donnée personnelle, ne propose aucun formulaire d'inscription, ni cookies de suivi.</p>

                <h3>Limitation de responsabilité</h3>
                    <p>Le site a un but purement pédagogique et informatif. L’auteur ne garantit pas l’exactitude absolue des informations et ne saurait être tenu responsable de l’usage fait des contenus.</p>

                <h3>Loi applicable</h3>
                    <p>Le site est soumis au droit français.</p>

                <h3>Droit et recours</h3>
                    <p>En cas de litige, veuillez me concacter via mon adresse mail où je tâcherai de répondre sous les plus brefs délais.</p>

                </main>";
    }

    function __toString()
    {
        return $this->debut . $this->main . $this->fin;
    }
}
?>