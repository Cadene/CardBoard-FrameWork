<?php
/**
 * CardBoard FrameWork
 * @author Remi Cadene
 */

    /**
     * Identification du fichier à inclure en fonction de l'url
     */

    if(!isset($_GET['p'])){
        $include_file = 'Accueil';
    }else if(!file_exists($_GET['p'].'.php')){
        $include_file = 'NotFound';
    }else{
        $include_file = $_GET['p'];
    }

    if(!isset($_GET['pp'])){
        $include_panier = 'Panier';
    }else if(!file_exists($_GET['pp'].'.php')){
        $include_panier = 'NotFound';
    }else{
        $include_panier = $_GET['pp'];
    }

    /**
     * Initialisation des objets des fichiers .inc,
     * utilisables aussi par les pages incluses
     */

    include('CoreException.inc');
    include('Outils.inc');
    include('BD.inc');

    // Connexion à la bd
    try{
        $BD = new BD();
    }catch (CoreException $e){
        echo $e->toHTML();
    }
    // Création de la boite à outils
    $Outils = new Outils();

    // Protection de $_POST contre les failles
    $Outils->sanitize_posts();

    /**
     *  Génération du HTML
     */

    echo $Outils->header();

    try
    {
        $BD->connect();

        $BD->verifierReservations(300);
        //setcookie("identite", "", time()-3600);
        print_r($_COOKIE);
        include($include_file.'.php');
        include($include_panier.'.php');

        $BD->close();
    }
    catch (CoreException $e)
    {
        echo $Outils->banniere('Error.php');
        echo "\n\n\t";
        echo $e->toHTML();
        echo "\n";
    }

    echo $Outils->footer();


// TODO gestion des input à 0 qui génère une exception de type args invalides


