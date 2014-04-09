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


<div class="list-group">
    <a href="<?= $href['AcceuilDescriptif']; ?>" class="list-group-item">
        <h4 class="list-group-item-heading">Descriptif d'un film</h4>
        <p class="list-group-item-text">Obtenez le synopsis, la liste des acteurs,
            le nom du réalisateur et bien d'autres informations en recherchant un film grace à son identifiant.</p>
    </a>
</div>
<div class="list-group">
    <a href="<?= $href['AccueilRecherche']; ?>" class="list-group-item">
        <h4 class="list-group-item-heading">Recherche de films</h4>
        <p class="list-group-item-text">Rechercher des films grace à des mots clés et autres.</p>
    </a>
</div>
<div class="list-group">
    <a href="<?= $href['IdentificationC']; ?>" class="list-group-item">
        <h4 class="list-group-item-heading">Commande de cassettes</h4>
        <p class="list-group-item-text">Choisissez vos films au préalable puis commandez en jusqu'à trois en même temps.</p>
    </a>
</div>
<div class="list-group">
    <a href="<?= $href['IdentificationD']; ?>" class="list-group-item">
        <h4 class="list-group-item-heading">Liste des cassettes détenues</h4>
        <p class="list-group-item-text">Obtenez la liste de vos films et cassettes empruntés.</p>
    </a>
</div>



