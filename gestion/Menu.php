<?php

    $content = "";

if(
    isset($_POST['nom'])
    && isset($_POST['pass'])
    && $_POST['nom'] == 'bond'
    && $_POST['pass'] == '007'
){
    $content .= $Outils->admin_menu();
}
else
{
    $content .= '<div>Données invalides, revenir à la <a href="index.php">page précédente</a> ?</div>';
}


echo $content;