<?php

if(isset($_POST['Pass']))
{
    $_POST['Code'] = $Outils->decrypt($_POST['Pass']);
}
else
{
    $abo = $BD->verifierAbonne($_POST['Nom'],$_POST['Code']);
    if( !isset($_POST['Nom']) || empty($_POST['Nom']) || !isset($_POST['Code']) || empty($_POST['Code']))
        throw new CoreException(103,"Args incorrectes.");
}

$abo['NbEmpruntables'] = 3 - $abo['NbCassettes'];

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

    <?php if($abo['NbEmpruntables']!=0): ?>
    <form action="ConfirmeCommande.php" method="post">
        <table>
            <?php for($i=1; $i<=$abo['NbEmpruntables']; $i++): ?>
            <tr>
                <td><input type="text" name="<?= 'NoFilm'.$i; ?>" placeholder="Numéro du film"/></td>
                <td>
                    <SELECT name="<?= 'Support'.$i; ?>">
                        <OPTION value="DVD">DVD</OPTION>
                        <OPTION value="VHS">VHS</OPTION>
                    </SELECT>
                </td>
            </tr>
            <?php endfor;?>
        </table>
        <input type="hidden" name="Code" value="<?= $Outils->encrypt($_POST['Code']); ?>"/>
        <input type="submit" value="Commander"/>
    </form>
    <?php endif; ?>




