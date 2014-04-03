<?php

if( !isset($_POST['NoFilm']) || empty($_POST['NoFilm']) ){
    throw new CoreException(101,"Numéro du film incorrecte.");
}else{

    // Récupération du film

    $sql = '';
    $sql .= 'SELECT ';
    $sql .= 'f.NoFilm, f.Titre, f.Nationalite, f.Realisateur, f.Couleur, f.Annee, f.Genre, f.Duree ';
    $sql .= 'FROM FILMS f ';
    $sql .= 'WHERE f.NoFilm = '.$_POST['NoFilm'];
    $sql .= ' LIMIT 1';

    $film = [];
    $rslt = $BD->exec($sql);
    if($row = $BD->fetch($rslt))
        $film = $row;

    // Récupération des acteurs du film

    $sql = '';
    $sql .= 'SELECT ';
    $sql .= 'a.Acteur ';
    $sql .= 'FROM ACTEURS a ';
    $sql .= 'WHERE a.NoFilm = '.$_POST['NoFilm'];

    $acteurs = [];
    $rslt = $BD->exec($sql);
    while($row = $BD->fetch($rslt))
        $acteurs[] = $row;
}
?>
<?= $Outils->banniere($include_file);?>

<h2>Films trouvés</h2>

<?php if(empty($film)):?>
    <div>Désolé, aucun film ne correspond à votre recherche. Voulez-vous <a href="AccueilDescriptif.php">réessayez</a> ?</div>
<?php else: ?>
    <table>
        <tr>
            <td>NoFilm</td>
            <td>Titre</td>
            <td>Nationalite</td>
            <td>Realisateur</td>
            <td>Couleur</td>
            <td>Annee</td>
            <td>Genre</td>
            <td>Duree</td>
            <td>Acteurs</td>
        </tr>
        <tr>
            <?php foreach($film as $f): ?>
            <td><?= $f;?></td>
            <?php endforeach;?>
            <td><?php foreach($acteurs as $k=>$acteur):
                echo ($k!=0 ? ', ' : '').current($acteur);
            endforeach;?></td>
        </tr>
    </table>

<?php endif; ?>




