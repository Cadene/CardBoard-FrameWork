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

    if(isset($_GET['admin'])){
        if(!file_exists('gestion/'.$_GET['admin'].'.php'))
            $include_admin = 'index';
        else
            $include_admin = $_GET['admin'];
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

    echo  $Outils->banniere($include_file);

    echo '<div class="container">';

    try
    {
        $BD->connect();

        $BD->verifierReservations(300);

        if(isset($include_admin))
        {
            echo $Outils->admin_banniere();
            echo '<div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Cadre de l\'administration</h3>
                </div>
                <div class="panel-body">';
            include('gestion/'.$include_admin.'.php');
            echo '</div>
             </div>';
        }
        else
        {

            echo '<div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Cadre des fonctions principales</h3>
                </div>
                <div class="panel-body">';
            include($include_file.'.php');
            echo '</div>
             </div>';


            echo '<div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Cadre du panier de l\'utilisateur</h3>
                </div>
                <div class="panel-body">';
            include($include_panier.'.php');
            echo '</div>
             </div>';
        }

        $BD->close();
    }
    catch (CoreException $e)
    {
        echo $Outils->banniere('Error.php');
        echo "\n\n\t";
        echo $e->toHTML();
        echo "\n";
    }

    echo '</div> <!-- /container -->';

    echo $Outils->footer();


// TODO gestion des input à 0 qui génère une exception de type args invalides


