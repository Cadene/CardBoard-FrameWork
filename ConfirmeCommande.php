<?php

if(!isset($_POST['Code']) || empty($_POST['Code'])) throw new CoreException(100,'Invalid Args');

/* Traitement des NoFilms et Supports */

$Supports = [];
for($i=1; $i<=3; $i++){
    if(isset($_POST['NoFilm'.$i]) && isset($_POST['Support'.$i])){
        $Supports[$_POST['NoFilm'.$i]] =  $_POST['Support'.$i];
    }
}

if(empty($Supports)) throw new CoreException(101,'Empty Args');

/* Traitement Code */

$_POST['Code'] = $Outils->decrypt($_POST['Code']);

/* Récupération des films et des cassettes */

$sql .= 'SELECT ';
$sql .= 'f.NoFilm, f.Titre, f.Realisateur, c.NoExemplaire, c.Support, c.Statut ';
$sql .= 'FROM FILMS f, CASSETTES c ';
$sql .= 'WHERE c.NoFilm = f.NoFilm AND (';
foreach($Supports as $NoFilm=>$Support){
    if(!isset($first))
        $first=true;
    else
        $sql .=  ' OR';
    $sql .= ' f.NoFilm = '.$NoFilm;
}
$sql .= ' )';
unset($first);

$films = [];
$resa = [];
$rslt = $BD->exec($sql);
$i=0;
while($rows = $BD->fetch($rslt))
{
    if(!isset($films[$rows['NoFilm']]))
    {
        $films[$rows['NoFilm']]['NoFilm'] = $rows['NoFilm'];
        $films[$rows['NoFilm']]['Titre'] = $rows['Titre'];
        $films[$rows['NoFilm']]['Realisateur'] = $rows['Realisateur'];
    }
    $films[$rows['NoFilm']]['Cassettes'][$rows['Statut']][$rows['Support']][] = $rows['NoExemplaire'];
    if($rows['Statut'] == "disponible"){
        $resa[$i]['NoExemplaire'] = $rows['NoExemplaire'];
        $resa[$i]['NoFilm'] = $rows['NoFilm'];
    }
    $i++;
}

/* Réservation des cassettes */
$date = date('Y-m-d H:i:s');
foreach($resa as $r){
    $sql = 'UPDATE ';
    $sql .= 'CASSETTES ';
    $sql .= 'SET Statut = "reservee"';
    $sql .= 'WHERE NoFilm = '.$r['NoFilm'];
    $sql .= ' AND NoExemplaire = '.$r['NoExemplaire'];
    $sql .= ';';
    $BD->exec($sql);
}
$sql = 'INSERT ';
$sql .= 'INTO EMPRES ';
$sql .= '(NoFilm, NoExemplaire, CodeAbonne, DateEmpRes) ';
$sql .= 'VALUES ';
foreach($resa as $k=>$r){
    $sql .= ($k==0 ? '' : ',') . '('.$r['NoFilm'].','.$r['NoExemplaire'].',"SERVEUR","'.$date.'")';
}
$BD->exec($sql);


if(empty($films)) throw new CoreException(102,'Empty Films');

?>
<?= $Outils->banniere($include_file); ?>

<h2>Confirmation de votre commande</h2>

<form action="ExecuteCommande.php" method="post">
    <table>
        <tr>
            <td>NoFilm</td>
            <td>Titre</td>
            <td>Realisateur</td>
            <td>Disponible</td>
        </tr>
        <?php $i=1; $nbDispo=0;
        foreach($films as $film): ?>
        <tr>
            <td><?= $film['NoFilm'];?></td>
            <td><?= $film['Titre'];?></td>
            <td><?= $film['Realisateur'];?></td>
            <?php /* Film non disponible */ ?>
            <?php if (!isset($film['Cassettes']['disponible'])): ?>
            <td>
                <span>Non</span>
            </td>
            <?php /* Support non disponible */ ?>
            <?php elseif (!isset($film['Cassettes']['disponible'][$Supports[$film['NoFilm']]])): ?>
            <?php $nbDispo++;?>
            <td>
                <span><?= $film['Cassettes']['disponible'][0]; ?></span>
                <SELECT name="<?= 'NoCassette'.$i; ?>">
                <?php foreach($film['Cassettes']['disponible'][0] as $NoCassette): ?>
                    <OPTION value="<?= $NoCassette;?>"><?= $NoCassette;?></OPTION>
                <?php endforeach; ?>
                </SELECT>
            </td>
            <?php /* Support disponible */ ?>
            <?php else: ?>
            <?php $nbDispo++;?>
            <td>
                <span>Oui</span>
                <SELECT name="<?= 'NoCassette'.$i; ?>">
                    <?php foreach($film['Cassettes']['disponible'][$Supports[$film['NoFilm']]] as $NoCassette): ?>
                        <OPTION value="<?= $NoCassette;?>"><?= $NoCassette;?></OPTION>
                    <?php endforeach; ?>
                </SELECT>
            </td>
            <?php endif; ?>
            <input type="hidden" name="NbFilm<?= $i; ?>" value="<?= $film['NoFilm']; ?>"/>
        </tr>
        <?php $i++;
        endforeach;?>
    </table>
    <?php if($nbDispo != 0): ?>
    <input type="hidden" name="Code" value="<?= $Outils->encrypt($_POST['Code']); ?>"/>
    <input type="submit" value="Commander"/>
    <?php endif; ?>
</form>

<form action="Commande.php" method="post">
    <input type="hidden" name="Pass" value="<?= $Outils->encrypt($_POST['Code']); ?>"/>
    <input type="submit" value="Revoir le choix"/>
</form>

