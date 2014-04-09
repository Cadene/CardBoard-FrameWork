<?php


if( !isset($_POST['NoFilm']) || empty($_POST['NoFilm']) ){
    throw new CoreException(101,"Numéro du film incorrecte.");
}else{

    // Récupération du film
    $sql = 'SELECT ';
    $sql .= 'f.NoFilm, f.Titre, f.Nationalite, f.Realisateur, f.Couleur, f.Annee, f.Genre, f.Duree ';
    $sql .= 'FROM FILMS f ';
    $sql .= 'WHERE f.NoFilm = '.$_POST['NoFilm'];
    $sql .= ' LIMIT 1';

    $film = [];
    $rslt = $BD->exec($sql);
    if($row = $BD->fetch($rslt))
        $film = $row;


    // Récupération des acteurs du film
    $sql = 'SELECT ';
    $sql .= 'a.Acteur ';
    $sql .= 'FROM ACTEURS a ';
    $sql .= 'WHERE a.NoFilm = '.$_POST['NoFilm'];

    $acteurs = [];
    $rslt = $BD->exec($sql);
    while($row = $BD->fetch($rslt))
        $acteurs[] = $row;
}
?>

<h4>Films trouvés</h4>

<?php if(empty($film)):?>
    <div>Désolé, aucun film ne correspond à votre recherche. Voulez-vous <a href="AccueilDescriptif.php">réessayez</a> ?</div>
<?php else: ?>
    <table class="table table-hover">
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
            <td>Panier</td>
        </tr>
        <tr>
            <?php foreach($film as $f): ?>
            <td><?= $f;?></td>
            <?php endforeach;?>
            <td><?php foreach($acteurs as $k=>$acteur):
                echo ($k!=0 ? ', ' : '').current($acteur);
            endforeach;?></td>
            <td><?= $Outils->form($include_file,'AjoutSelection','Ajouter',array(
                    'name' => 'NoFilm', 'value' => $film['NoFilm']
                ));?></td>
        </tr>
    </table>

<?php endif; ?>




