<?php

/* Traitement Code */
$Outils->verifierPOST('Code');
$_POST['Code'] = $Outils->decrypt($_POST['Code']);

$Exemplaires = [];
for($i=1; $i<=3; $i++){
    if(isset($_POST['NoFilm'.$i]) && isset($_POST['NoExemplaire'.$i])){
        $Exemplaires[$_POST['NoFilm'.$i]] =  $_POST['NoExemplaire'.$i];
    }
}
if(empty($Exemplaires)) throw new CoreException();

/* Récupération des Cassettes */
$sql .= 'SELECT ';
$sql .= 'f.NoFilm, c.NoExemplaire, f.Titre, f.Realisateur, c.Support ';
$sql .= 'FROM FILMS f, CASSETTES c ';
$sql .= 'WHERE c.NoFilm = f.NoFilm AND (';
$k=0;
foreach($Exemplaires as $NoFilm=>$NoExemplaire){
    $sql .= ($k==0 ? '' : ' OR ') . '(c.NoFilm = '.$NoFilm.' AND c.NoExemplaire = '.$NoExemplaire.')';
    $k++;
}
$sql .= ' )';
$rslt = $BD->exec($sql);
$Cassettes = [];
while($row = $BD->fetch($rslt)){
    $Cassettes[] = $row;
}

$BD->enleverReservations($_POST['Code']);

$BD->emprunterCassettes($Exemplaires, $_POST['Code']);

?>
<?= $Outils->banniere($include_file); ?>

<h2>Récapitulatif de votre commande</h2>

<table>
    <tr>
        <td>NoFilm</td>
        <td>NoExemplaire</td>
        <td>Titre</td>
        <td>Réalisateur</td>
        <td>Support</td>
    </tr>
    <?php foreach($Cassettes as $cassette): ?>
    <tr>
        <?php foreach($cassette as $c): ?>
        <td><?= $c; ?></td>
        <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
</table>


