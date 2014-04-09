<?php

$Outils->verifierIdentite($BD);

// Récupération des Videos possédées

$sql = 'SELECT ';
$sql .= 'e.NoFilm, e.NoExemplaire, e.DateEmpRes, f.Titre, f.Realisateur  ';
$sql .= 'FROM EMPRES e, FILMS f ';
$sql .= 'WHERE e.CodeAbonne = "'.$_POST['Code'].'"';
$sql .= ' AND f.NoFilm = e.NoFilm';

$films = [];
$rslt = $BD->exec($sql);
while($row = $BD->fetch($rslt))
    $films[] = $row;

?>

<h4>Films trouvés</h4>

<?php if(empty($films)):?>
    <div>Vous n'avez emprunté aucun film en ce moment. En <a href="IdentificationC.php".php">commander</a> ?</div>
<?php else: ?>
    <table class="table table-hover">
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




