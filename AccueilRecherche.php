<?php

/* Récupération de tous les genres, réalisateurs et acteurs */
$genres = $BD->findOne('Genre','FILMS');
$realisateurs = $BD->findOne('Realisateur','FILMS');
$acteurs = $BD->findOne('Acteur','ACTEURS');

?>

   <h4>Recherche de films</h4>

    <br/>

    <form action="?p=Recherche" method="post" role="form">
        <div class="form-group">
            <label for="exampleInputEmail1">Mots composants le titre</label>
            <input type="text" class="form-control" name="mots" placeholder="2001 l'odyssée">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Support</label>
            <SELECT name="support" class="form-control">
                <OPTION value="-1">Indifférent</OPTION>
                <OPTION value="1">DVD</OPTION>
                <OPTION value="2">VHS</OPTION>
            </SELECT>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Statut</label>
            <SELECT name="dispo" class="form-control">
                <OPTION value="-1">Indifférent</OPTION>
                <OPTION value="1">Disponible</OPTION>
            </SELECT>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Genre</label>
            <SELECT name="genre" class="form-control">
                <OPTION selected value="-1">Indifférent</OPTION>
                <?php foreach($genres as $genre): ?>
                <OPTION value="<?= $genre;?>"><?= $genre;?></OPTION>
                <? endforeach; ?>
            </SELECT>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Réalisateur</label>
            <SELECT name="realisateur" class="form-control">
                <OPTION selected value="-1">Indifférent</OPTION>
                <?php foreach($realisateurs as $real): ?>
                <OPTION value="<?= $real;?>"><?= $real;?></OPTION>
                <? endforeach; ?>
            </SELECT>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Acteurs</label>
            <SELECT name="acteur" class="form-control">
                <OPTION selected value="-1">Indifférent</OPTION>
                <?php foreach($acteurs as $acteur): ?>
                <OPTION value="<?= $acteur;?>"><?= $acteur;?></OPTION>
                <? endforeach; ?>
            </SELECT>
        </div>
        <br/>
        <button type="submit" class="btn btn-info btn-block">Rechercher</button>
    </form>