<?php

/* Verification des $_POST */

$Outils->verifierPOST('mots',true);
foreach(array('support','dispo','genre','realisateur','acteur') as $field)
    $Outils->verifierPOST($field);

/* Récupération des films */

$sql = "";
$sql .= 'SELECT ';
$sql .= 'f.NoFilm, f.Titre, f.Nationalite, f.Realisateur, f.Couleur, f.Annee, f.Genre, f.Duree, f.Synopsis ';
$sql .= 'FROM FILMS f ';
$sql .= 'LEFT JOIN CASSETTES c ON f.NoFilm = c.NoFilm ';
$sql .= 'LEFT JOIN ACTEURS a ON f.NoFilm = a.NoFilm ';
$sql .= 'WHERE 1=1 ';

if($_POST['realisateur'] != -1)
    $sql .= ' AND f.Realisateur = "'.$_POST['realisateur'].'"';

if($_POST['genre'] != -1)
    $sql .= ' AND f.Genre = "'.$_POST['genre'].'"';

if($_POST['support'] != -1)
    $sql .= ' AND c.Support = "'.$_POST['support'].'"';

if($_POST['acteur'] != -1)
    $sql .= ' AND a.Acteur = "'.$_POST['acteur'].'"';

if(!empty($_POST['mots'])){
    foreach(explode(" ",$_POST['mots']) as $mot){
        $sql .= ' AND f.Titre LIKE \'%'.$mot.'%\'';
    }
}

$sql .= ' GROUP BY f.NoFilm';

echo $sql;

$films = [];
$rslt = $BD->exec($sql);
while($row = $BD->fetch($rslt))
    $films[] = $row;


?>

    <h2>Films trouvés</h2>

    <?php if(empty($films)):?>
    <div>Désolé, aucun film ne correspond à votre recherche. Voulez-vous <a href="AccueilRecherche.php">réessayez</a> ?</div>
    <?php else: ?>
    <?php
        $tds = array('NoFilm','Titre','Nationalite','Realisateur','Couleur','Annee','Genre','Duree','Synopsis');
    ?>
    <table class="table table-hover">
        <tr>
        <?php foreach($tds as $td): ?>
            <td><?= $td; ?></td>
        <?php endforeach; ?>
            <td>Panier</td>
        </tr>
    <?php foreach($films as $film): ?>
        <tr>
            <?php foreach($tds as $td): ?>
            <td><?= $film[$td] ;?></td>
            <?php endforeach;?>
            <td><?= $Outils->form('Accueil','AjoutSelection','Ajouter',array(
                    'name' => 'NoFilm', 'value' => $film['NoFilm']
                ));?></td>
        </tr>
    <?php endforeach; ?>
    </table>

    <?php endif; ?>




