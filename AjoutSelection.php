<?php


$Outils->verifierPOST('NoFilm');

if(!isset($_COOKIE['selection']))
{
    $Outils->setSelection(0,1);
    $Outils->setSelection(1,$_POST['NoFilm']);
}
else
{
    $nb_films = $_COOKIE['selection'][0];
    $nb_films ++;

    for($i=1; $i<$nb_films;$i++)
    {
        if($_COOKIE['selection'][$i] == $_POST['NoFilm'])
        {
            throw new CoreException(12,'Vous avez déjà ajouté ce film à votre panier.');
        }
    }

    $Outils->setSelection(0,$nb_films);
    $Outils->setSelection($nb_films,$_POST['NoFilm']);
}

?>

<div>Film ajouté</div>