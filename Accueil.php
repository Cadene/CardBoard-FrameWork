<?php

$href['AcceuilDescriptif'] = "?p=AccueilDescriptif";
$href['AccueilRecherche'] = "?p=AccueilRecherche";

if(!isset($_COOKIE['identite'])){
    $href['IdentificationC'] = "?p=IdentificationC";
    $href['IdentificationD'] = "?p=IdentificationD";
}else{

    $href['IdentificationC'] = "?p=Commande";
    $href['IdentificationD'] = "?p=Detenues";
}

?>


    <h1>VideoExpress <small>© Rémi Cadène</small></h1>

    <div>
        <div>
            <a href="<?= $href['AcceuilDescriptif']; ?>">Descriptif d'un film</a>
        </div>
        <div>
            <a href="<?= $href['AcceuilRecherche']; ?>">Recherche de films</a>
        </div>
        <div>
            <a href="<?= $href['IdentificationC']; ?>">Commande de cassettes</a>
        </div>
        <div>
            <a href="<?= $href['IdentificationD']; ?>">Liste des cassettes détenues</a>
        </div>
    </div>