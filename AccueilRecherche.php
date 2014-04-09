<?php

/* Récupération de tous les genres, réalisateurs et acteurs */
$genres = $BD->findOne('Genre','FILMS');
$realisateurs = $BD->findOne('Realisateur','FILMS');
$acteurs = $BD->findOne('Acteur','ACTEURS');

?>

<?= $Outils->banniere($include_file); ?>

    <h2>Recherche de films</h2>

    <form action="?p=Recherche" method="post">
        <input type="text" name="mots" placeholder="Mots composants le titre"/>
        <SELECT name="support">
            <OPTION value="-1">Indifférent</OPTION>
            <OPTION value="1">DVD</OPTION>
            <OPTION value="2">VHS</OPTION>
        </SELECT>
        <SELECT name="dispo">
            <OPTION value="-1">Indifférent</OPTION>
            <OPTION value="1">Disponible</OPTION>
        </SELECT>
        <SELECT name="genre">
            <OPTION selected value="-1">Indifférent</OPTION>
            <?php foreach($genres as $genre): ?>
            <OPTION value="<?= $genre;?>"><?= $genre;?></OPTION>
            <? endforeach; ?>
        </SELECT>
        <SELECT name="realisateur">
            <OPTION selected value="-1">Indifférent</OPTION>
            <?php foreach($realisateurs as $real): ?>
            <OPTION value="<?= $real;?>"><?= $real;?></OPTION>
            <? endforeach; ?>
        </SELECT>
        <SELECT name="acteur">
            <OPTION selected value="-1">Indifférent</OPTION>
            <?php foreach($acteurs as $acteur): ?>
            <OPTION value="<?= $acteur;?>"><?= $acteur;?></OPTION>
            <? endforeach; ?>
        </SELECT>
        <input type="submit" value="Rechercher"/>
    </form>