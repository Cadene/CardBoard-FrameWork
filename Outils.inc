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
        $s = '';
        $s .= '<!doctype html>'."\n";
        $s .= '<html lang="fr">'."\n";
        $s .= '<head>'."\n";
        $s .= '<meta charset="utf-8">'."\n";
        $s .= '<title>VideoEXPRESS</title>'."\n";
        $s .= '<link rel="stylesheet" href="css/bootstrap.min.css">'."\n";
        $s .= '<link href="css/navbar-fixed-top.css" rel="stylesheet">'."\n";
        $s .= '</head>'."\n\n";
        $s .= '<body>'."\n";
        return $s;
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
        $s = '<div class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="#">'.$page.' <small>© '.$auteurs.'</small></a>
                 </div>
                 <div class="navbar-collapse collapse">';
        if($current != 'Accueil')
            $s .= $this->menu($current);
        $s .= '<ul class="nav navbar-nav navbar-right">
                <li><a href="?admin">Admin</a></li>
              </ul>
              </div><!--/.nav-collapse -->
          </div>
        </div>';

        return $s;
    }

    public function admin_banniere($current="",$page='VideoExpress',$auteurs='Rémi Cadène'){
        $s = "\t".'<h1>'.$page.' <small>© '.$auteurs.'</small></h1>'."\n";
        $s .= $this->admin_menu($current);
        return $s;
    }

    public function menu($current)
    {
        $href['AccueilDescriptif'] = "?p=AccueilDescriptif";
        $href['AccueilRecherche'] = "?p=AccueilRecherche";

        if(!isset($_COOKIE['identite'])){
            $href['IdentificationC'] = "?p=IdentificationC";
            $href['IdentificationD'] = "?p=IdentificationD";
        }else{

            $href['IdentificationC'] = "?p=Commande";
            $href['IdentificationD'] = "?p=Detenues";
        }

        $s = '<ul class="nav navbar-nav">';
        if($current != 'Accueil'){
            $s .= '<li>'."\n";
            $s .=   '<a href="?p=Accueil">Accueil</a>'."\n";
            $s .= '</li>'."\n";
        }
        if($current != 'AccueilDescriptif'){
            $s .= '<li>'."\n";
            $s .=   '<a href="'.$href['AccueilDescriptif'].'">Trouver</a>'."\n";
            $s .= '</li>'."\n";
        }
        if($current != 'AccueilRecherche'){
            $s .= '<li>'."\n";
            $s .=   '<a href="'.$href['AccueilRecherche'].'">Rechercher</a>'."\n";
            $s .= '</li>'."\n";
        }
        if($current != 'IdentificationC'){
            $s .= '<li>'."\n";
            $s .=   '<a href="'.$href['IdentificationC'].'">Commander</a>'."\n";
            $s .= '</li>'."\n";
        }
        if($current != 'IdentificationD'){
            $s .= '<li>'."\n";
            $s .=   '<a href="'.$href['IdentificationD'].'">Vos Cassettes</a>'."\n";
            $s .= '</li>'."\n";
        }
        $s .= '</ul>'."\n";
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
            <a href="?p=AccueilRetour">Retour de cassettes</a>
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
        $s = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
               <script src="js/bootstrap.min.js"></script>';
        $s .= "\n".'</body>'."\n";
        $s .= '</html>'."\n";
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
            setcookie('identite[0]',$Nom,time()+3600);
            setcookie('identite[1]',$this->encrypt($Code),time()+3600);
        }
    }

    public function getCOOKIE(){
        if(isset($_COOKIE['identite'])){
            $data['Nom'] = $_COOKIE['identite'][0];
            $data['Code'] = $this->decrypt($_COOKIE['identite'][1]);
            return $data;
        }
        return false;
    }

    public function verifierCOOKIE(){
        $identite = $this->getCOOKIE();
        if($identite === false){
            foreach(array('Nom','Code') as $field) { $this->verifierPOST($field); }
        }else{
            $_POST['Nom'] = $identite['Nom'];
            $_POST['Code'] = $identite['Code'];
        }
    }

    public function verifierIdentite($BD){
        $this->verifierCOOKIE();
        $abo = $BD->verifierAbonne($_POST['Nom'],$_POST['Code']);
        $this->creerCOOKIE($_POST['Nom'],$_POST['Code']);
        return $abo;
    }

    public function supportContraire($support){
        if($support == 'VHS')
            return 'DVD';
        return 'VHS';
    }

    public function formPanier($p,$pp,$value,$hidden=null){
        $s = '';
        $s .= '<form action="?p='.$p.'&pp='.$pp.'" method="post">'."\n";
        $s .= '<button type="submit" class="btn btn-default btn-block">'.$value.'</button>'."\n";
        if($hidden != null)
            $s .= '<input type="hidden" name="'.$hidden['name'].'" value="'.$hidden['value'].'"/>'."\n";
        $s .= '</form>'."\n";
        return $s;
    }

    public function form($p,$pp,$value,$hidden=null){
        $s = '';
        $s .= '<form action="?p='.$p.'&pp='.$pp.'" method="post">'."\n";
        $s .= '<input type="submit" value="'.$value.'"/>'."\n";
        if($hidden != null)
            $s .= '<input type="hidden" name="'.$hidden['name'].'" value="'.$hidden['value'].'"/>'."\n";
        $s .= '</form>'."\n";
        return $s;
    }

    public function getPanierNoFilms(){
        $nb_films = $_COOKIE['selection'][0];
        for($i=1; $i<=$nb_films; $i++){
            $NoFilms[] = $_COOKIE['selection'][$i];
        }
        return $NoFilms;
    }

    public function setSelection($i,$value){
        $time = time()+60*60*24*30;
        setcookie('selection['.$i.']',$value,$time);
    }

    public function getSelection($i){
        return $_COOKIE['selection'][$i];
    }



}