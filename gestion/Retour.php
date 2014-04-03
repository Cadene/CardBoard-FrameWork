<?php

if( !isset($_POST['NoFilm']) || empty($_POST['NoFilm'])
    || !isset($_POST['NoExemplaire']) || empty($_POST['NoExemplaire'])){
    throw new CoreException(101,"Args vides.");
}else{

    // Récupération des informations de la cassette
    $sql = 'SELECT ';
    $sql .= 'f.NoFilm, c.NoExemplaire, f.Titre, f.Realisateur, c.Support ';
    $sql .= 'FROM FILMS f, CASSETTES c ';
    $sql .= 'WHERE f.NoFilm = '.$_POST['NoFilm'];
    $sql .= ' AND c.NoFilm = '.$_POST['NoFilm'];
    $sql .= ' AND c.NoExemplaire = '.$_POST['NoExemplaire'];
    $sql .= ' LIMIT 1';
    $rslt = $BD->exec($sql);
    $cassette = $BD->fetch($rslt);
    if(empty($cassette)) throw new CoreException(102,'Pas de cassette correspondant');

    // Récupération de l'identifiant de l'emprunteur
    $sql = 'SELECT ';
    $sql .= 'CodeAbonne ';
    $sql .= 'FROM EMPRES ';
    $sql .= 'WHERE NoFilm = '.$_POST['NoFilm'];
    $sql .= ' AND NoExemplaire = '.$_POST['NoExemplaire'];
    $sql .= ' LIMIT 1';
    $rslt = $BD->exec($sql);
    $abo = $BD->fetch($rslt);
    if(empty($abo)) throw new CoreException(102,'Pas d\'emprunteur correspondant');

    // Mise à jour de la disponibilité de la cassette
    $sql = 'UPDATE ';
    $sql .= 'CASSETTES ';
    $sql .= 'SET Statut = "Disponible" ';
    $sql .= 'WHERE NoFilm = '.$_POST['NoFilm'];
    $sql .= ' AND NoExemplaire = '.$_POST['NoExemplaire'];
    $BD->exec($sql);

    // Mise à jour du nombre de cassettes de l'abonne
    $sql = 'UPDATE ';
    $sql .= 'ABONNES ';
    $sql .= 'SET NbCassettes = NbCassettes - 1 ';
    $sql .= 'WHERE Code = "'.current($abo).'"';
    $BD->exec($sql);

    // Suppression de l'emprunt
    $sql = 'DELETE ';
    $sql .= 'FROM EMPRES ';
    $sql .= 'WHERE NoFilm = '.$_POST['NoFilm'];
    $sql .= ' AND NoExemplaire = '.$_POST['NoExemplaire'];
    $BD->exec($sql);

}
?>
<?= $Outils->admin_banniere($include_file);?>

<h2>Retour d'une cassette</h2>

    <table>
        <tr>
            <td>NoFilm</td>
            <td>NoExemplaire</td>
            <td>Titre</td>
            <td>Realisateur</td>
            <td>Support</td>
        </tr>
        <tr>
            <?php foreach($cassette as $c): ?>
            <td><?= $c;?></td>
            <?php endforeach;?>
        </tr>
    </table>



