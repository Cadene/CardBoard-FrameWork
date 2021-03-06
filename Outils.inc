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

    public function admin_banniere($current="",$page='VideoExpress',$auteurs='Rémi Cadène'){
        $s = "\t".'<h1>'.$page.' <small>© '.$auteurs.'</small></h1>'."\n";
        $s .= $this->admin_menu($current);
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

    public function encrypt($data) {
        $key = "secret";  // Clé de 8 caractères max
        $data = serialize($data);
        $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td,$key,$iv);
        $data = base64_encode(mcrypt_generic($td, '!'.$data));
        mcrypt_generic_deinit($td);
        return $data;
    }

    public function decrypt($data) {
        $key = "secret";
        $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td,$key,$iv);
        $data = mdecrypt_generic($td, base64_decode($data));
        mcrypt_generic_deinit($td);

        if (substr($data,0,1) != '!')
            return false;

        $data = substr($data,1,strlen($data)-1);
        return unserialize($data);
    }

    public function date(){
        return date('Y-m-d H:i:s');
    }

    // TODO gestion des Regexp
    /**
     * Vérification de la validité des champs
     * Décryptage si field est Pass ou Code
     * Gestion du COOKIE
     *
     * @param $field
     * @param $canEmpty
     * @throws CoreException
     */
    public function verifierPOST($field,$canEmpty=false)
    {
        if(isset($_COOKIE['identite'])){
            $_POST['Nom'] = $_COOKIE['identite'][0];
            $_POST['Code'] = $this->decrypt($_COOKIE['identite'][1]);
        }
        $error = 'Le champ '.$field.' de la variable globale $_POST ';

        if (!isset($_POST[$field]))
            throw new CoreException(0, $error.'n\'est pas déclarée.');

        if (!$canEmpty && empty($_POST[$field]))
            throw new CoreException(1, $error.'est vide.');

        if($field == 'Pass'){
            $_POST['Code'] = $this->decrypt($_POST['Pass']);
        }

    }

    /**
     * Création du Cookie identité si pas déjà fait
     * Il sera supprimé à la fermeture du navigateur
     *
     * @param $Nom
     * @param $Code
     */
    public function creerCOOKIE($Nom,$Code){
        if(!isset($_COOKIE['identite'])){
            setcookie(
                'identite',
                array(
                    $Nom,
                    $this->encrypt($Code)
                ),
                0
            );
        }
    }


    public function supportContraire($support){
        if($support == 'VHS')
            return 'DVD';
        return 'VHS';
    }
}