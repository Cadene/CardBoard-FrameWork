<?php

foreach(array('Nom','Code') as $field) { $Outils->verifierPOST($field); }

$BD->verifierAbonne($_POST['Nom'],$_POST['Code']);

//$Outils->creerCOOKIE($_POST['Nom'],$_POST['Code']);

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




