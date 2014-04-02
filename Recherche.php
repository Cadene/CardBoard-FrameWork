<?php

if(
    !isset($_POST['mots'])
    || !isset($_POST['support']) || empty($_POST['support'])
    || !isset($_POST['dispo']) || empty($_POST['dispo'])
    || !isset($_POST['genre']) || empty($_POST['genre'])
    || !isset($_POST['realisateur']) || empty($_POST['realisateur'])
    || !isset($_POST['acteur']) || empty($_POST['acteur'])
){
    throw new CoreException();
}else{

     // Récupération des films

    $sql = "";
    $sql .= 'SELECT DISTINCT ';
    $sql .= 'f.NoFilm, f.Titre, f.Nationalite, f.Realisateur, f.Couleur, f.Annee, f.Genre, f.Duree, f.Synopsis ';
    $sql .= 'FROM FILMS f ';
    $sql .= 'WHERE 1=1 ';

    if($_POST['realisateur'] != -1)
        $sql .= ' AND f.Realisateur = "'.$_POST['realisateur'].'"';

    if($_POST['genre'] != -1)
        $sql .= ' AND f.Genre = "'.$_POST['genre'].'"';

    if(!empty($_POST['mots'])){
        foreach(explode(" ",$_POST['mots']) as $mot){
            $sql .= ' AND f.Titre LIKE \'%'.$mot.'%\'';
        }
    }

    $films = [];
    $rslt = $BD->exec($sql);
    while($row = $BD->fetch($rslt))
        $films[] = $row;

}
?>
<?= $Outils->banniere();?>

    <h2>Films trouvés</h2>

    <?php if(empty($films)):?>
    <div>Désolé, aucun film ne correspond à votre recherche. Voulez-vous <a href="AccueilRecherche.php">réessayez</a> ?</div>
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
            <td>Synopsis</td>
        </tr>
    <?php foreach($films as $film): ?>
        <tr>
            <?php foreach($film as $f): ?>
            <td><?= $f;?></td>
            <?php endforeach;?>
        </tr>
    <?php endforeach;?>
    </table>

    <?php endif; ?>




