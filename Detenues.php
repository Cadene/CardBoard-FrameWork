<?php

if( !isset($_POST['Nom']) || empty($_POST['Nom'])
    || !isset($_POST['Code']) || empty($_POST['Code'])){
    throw new CoreException(101,"Args incorrectes.");
}else{

    // Récupération de l'abonné

    $sql = '';
    $sql .= 'SELECT ';
    $sql .= 'a.Code, a.Nom ';
    $sql .= 'FROM ABONNES a ';
    $sql .= 'WHERE a.Code = '.$_POST['Code'];
    $sql .= ' LIMIT 1';

    $abo = [];
    $rslt = $BD->exec($sql);
    if($row = $BD->fetch($rslt))
        $abo = $row;
    else
        throw new CoreException(101,'Le Code n\'existe pas.');
    if($abo['Nom'] != $_POST['Nom']){
        throw new CoreException(101,'Le Nom associé au Code n\'est pas le même.');
    }

    // Récupération des Videos possédées

    $sql = '';
    $sql .= 'SELECT ';
    $sql .= 'e.NoFilm, e.NoExemplaire, e.DateEmpRes, f.Titre, f.Realisateur  ';
    $sql .= 'FROM EMPRES e, FILMS f ';
    $sql .= 'WHERE e.CodeAbonne = '.$_POST['Code'];

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




