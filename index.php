<?php
/**
 * CardBoard FrameWork
 * @author Remi Cadene
 */

    /**
     * Identification du fichier à inclure en fonction de l'url
     */

    $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $parse_url = parse_url($url);

    if($parse_url['path'] == '/')
    {
        $include_file = 'Accueil.php';
    }
    else
    {
        $explode_url = explode('/',$parse_url['path']);
        $include_file = $explode_url[1];

        if($include_file == 'gestion')
        {
            if(!isset($explode_url[2]) || empty($explode_url[2]))
            {
                $include_file = 'gestion/index.php';
            }
            else
            {
                $include_file = 'gestion/'.$explode_url[2];
            }
        }

        if(
            !file_exists($include_file) || isset(explode('.inc',$include_file)[1])
        ){
            $include_file = 'NotFound.php';
        }
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

        include($include_file);

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


