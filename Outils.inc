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
    public function banniere($current,$page='VideoExpress',$auteurs='Rémi Cadène'){
        $s = "\t".'<h1>'.$page.' <small>© '.$auteurs.'</small></h1>'."\n";
        $s .= $this->menu($current);
        return $s;

    }

    public function menu($current)
    {
        $s = '
    <div id="menu">';
        if($current != 'Accueil.php'){
        $s .= '
        <div>
            <a href="Accueil.php">Accueil</a>
        </div>';
        }
        if($current != 'AccueilDescriptif.php'){
        $s .= '
        <div>
            <a href="AccueilDescriptif.php">Descriptif d\'un film</a>
        </div>';
        }
        if($current != 'AccueilRecherche.php'){
        $s .= '
        <div>
            <a href="AccueilRecherche.php">Recherche de films</a>
        </div>';
        }
        if($current != 'IdentificationC.php'){
        $s .= '
        <div>
            <a href="IdentificationC.php">Commande de cassettes</a>
        </div>';
        }
        if($current != 'IdentificationD.php'){
        $s .= '
        <div>
            <a href="IdentificationD.php">Liste des cassettes détenues</a>
        </div>';
        }
        $s .= '
    </div>';
        return $s;
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
        $s = "\n".'</body>'."\n";
        $s.= '</html>'."\n";
        return $s;
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