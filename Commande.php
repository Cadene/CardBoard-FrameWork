<?php


foreach(array('Nom','Code') as $field) { $Outils->verifierPOST($field); }

$abo = $BD->verifierAbonne($_POST['Nom'],$_POST['Code']);

$Outils->creerCOOKIE($_POST['Nom'],$_POST['Code']);

$abo['NbEmpruntables'] = 3 - $abo['NbCassettes'];

?>
<?= $Outils->banniere($include_file);?>

    <h2>Commander des films</h2>

    <div>
        <?php if($abo['NbEmpruntables']<=0):
            echo 'Vous ne pouvez plus emprunter de cassettes.';
        else:
            echo 'Vous pouvez encore emprunter \''.$abo['NbEmpruntables'].'\' cassette(s).';
        endif; ?>
    </div>

    <?php if($abo['NbEmpruntables']>0): ?>
    <form action="ConfirmeCommande.php" method="post">
        <table>
            <?php for($i=1; $i<=$abo['NbEmpruntables']; $i++): ?>
            <tr>
                <td><input type="text" name="<?= 'NoFilm'.$i; ?>" placeholder="Numéro du film"/></td>
                <td>
                    <input type="radio" name="<?= 'Support'.$i; ?>" value="DVD"/>DVD
                    <input type="radio" name="<?= 'Support'.$i; ?>" value="VHS"/>VHS
                </td>
            </tr>
            <?php endfor;?>
        </table>
        <input type="hidden" name="Code" value="<?= $Outils->encrypt($_POST['Code']); ?>"/>
        <input type="submit" value="Commander"/>
    </form>
    <?php endif; ?>




