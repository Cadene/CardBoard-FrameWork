<?php

class Outils {
    /**
     * Fonction Header
     *
     * Affiche le haut de page
     *
     * @return string
     */
    public function header(){
    return '
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>VideoEXPRESS</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

';
    }

    /**
     * Fonction banniere
     *
     * Renvoie le titre et le menu
     *
     * @param string $page
     * @param string $auteurs
     *
     * @return string
     */
    public function banniere($page='VideoExpress',$auteurs='Rémi Cadène'){
        return '
    <h1>'.$page.'<small>© '.$auteurs.'</small></h1>

    <div id="menu">
        <div>
            <a href="Accueil.php">Accueil</a>
        </div>
        <div>
            <a href="AccueilDescriptif.php">Descriptif d\'un film</a>
        </div>
        <div>
            <a href="AccueilRecherche.php">Recherche de films</a>
        </div>
        <div>
            <a href="IdentificationC.php">Commande de cassettes</a>
        </div>
        <div>
            <a href="IdentificationD.php">Liste des cassettes détenues</a>
        </div>
    </div>';
    }

    public function admin_menu(){
        return '
        <div id="menu">
        <div>
            <a href="#">Retour de cassettes</a>
        </div>
        <div>
            <a href="#">Enregistrer de nouveaux aboonés</a>
        </div>
        <div>
            <a href="#">Modifier des fiches d\'abonnés</a>
        </div>
        <div>
            <a href="#">Radier des abonnés</a>
        </div>
        <div>
            <a href="AccueilRetour.php">Retour de cassettes</a>
        </div>
    </div>';
    }

    /**
     * Fonction Footer
     *
     * Renvoie le bas de page
     *
     * @return string
     */

    public function footer(){
    return '
</body>
</html>

';
    }

    public function sanitize_posts(){
        foreach($_POST as $k=>$v){
            $_POST[$k] = $this->sanitize_string($v);
        }
    }

    public function sanitize_string($str) {
        if (get_magic_quotes_gpc()) {
            $sanitize = mysql_real_escape_string(stripslashes($str));
        } else {
            $sanitize = mysql_real_escape_string($str);
        }
        return $sanitize;
    }

}