<?php

if( !isset($_POST['Nom']) || empty($_POST['Nom'])
    || !isset($_POST['Pass']) || empty($_POST['Pass']))
{
    throw new CoreException(103,"Args incorrectes.");
}
else
{
    $abo = $BD->verifierAbonne($_POST['Nom'],$_POST['Pass']);
    $abo['NbEmpruntables'] = 3 - $abo['NbCassettes'];
}
?>
<?= $Outils->banniere($include_file);?>

    <h2>Commander des films</h2>

    <div>
        <?php if($abo['NbEmpruntables']==0):
            echo 'Vous ne pouvez plus emprunter de cassettes.';
        else:
            echo 'Vous pouvez encore emprunter \''.$abo['NbEmpruntables'].'\' cassette(s).';
        endif; ?>
    </div>

    <?php if($abo['NbEmpruntables']==0): ?>
    <form action="ConfirmeCommande.php" method="post">
        <table>
            <?php for($i=1; $i<=$abo['NbEmpruntables']; $i++): ?>
            <tr>
                <td><input type="text" name="<?= 'NoFilm'.$i; ?>"/></td>
                <td>Realisateur</td>
            </tr>
            <?php endfor;?>
        </table>
    </form>
    <?php endif; ?>




