<?php

$NoFilmsSupp = array();
for($i=0; $i<$_POST['nb_films']; $i++){
    if(isset($_POST['NoFilm_'.$i]))
        $NoFilmsSupp[] = $_POST['NoFilm_'.$i];
}

$NoFilms = $Outils->getPanierNoFilms();

$NoFilms = array_diff($NoFilms,$NoFilmsSupp);

$Outils->setSelection(0,count($NoFilms));
for($i=1;$i<=count($NoFilms);$i++){
    $Outils->setSelection($i,$NoFilms[$i]);
}

?>

<div><?= count($NoFilmsSupp);?> film(s) supprimé(s).</div>

<?= $Outils->form($include_file,'VoirSelection','Voir le contenu du panier');