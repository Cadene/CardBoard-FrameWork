<?php

if( !isset($_POST['Nom']) || empty($_POST['Nom'])
    || !isset($_POST['Code']) || empty($_POST['Code']))
{
    throw new CoreException(103,"Args incorrectes.");
}
else
{
    // Récupération de l'abonné

    $sql = '';
    $sql .= 'SELECT ';
    $sql .= 'a.Code, a.Nom ';
    $sql .= 'FROM ABONNES a ';
    $sql .= 'WHERE a.Code = "'.$_POST['Code'].'"';
    $sql .= ' LIMIT 1';

    $rslt = $BD->exec($sql);
    $abo = $BD->fetch($rslt);
    if(empty($abo)){
        throw new CoreException(102,'Le Code n\'existe pas.');
    }
    if($abo['Nom'] != $_POST['Nom']){
        throw new CoreException(101,'Le Nom associé au Code n\'est pas le même.');
    }

    // Récupération des Videos possédées

    $sql = '';
    $sql .= 'SELECT ';
    $sql .= 'e.NoFilm, e.NoExemplaire, e.DateEmpRes, f.Titre, f.Realisateur  ';
    $sql .= 'FROM EMPRES e, FILMS f ';
    $sql .= 'WHERE e.CodeAbonne = "'.$_POST['Code'].'"';
    $sql .= ' AND f.NoFilm = e.NoFilm';

    $films = [];
    $rslt = $BD->exec($sql);
    while($row = $BD->fetch($rslt))
        $films[] = $row;
}
?>
<?= $Outils->banniere($include_file);?>

<h2>Films trouvés</h2>

<?php if(empty($films)):?>
    <div>Vous n'avez emprunté aucun film en ce moment. En <a href="IdentificationC.php".php">commander</a> ?</div>
<?php else: ?>
    <table>
        <tr>
            <td>NoFilm</td>
            <td>NoExemplaire</td>
            <td>DateEmpRes</td>
            <td>Titre</td>
            <td>Realisateur</td>
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




