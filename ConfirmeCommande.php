<?php

$Outils->verifierPOST('Code');

/* Traitement des Supports associés à un film */

$Supports = [];
$NoFilms = [];
for($i=1; $i<=3; $i++)
{
    if(
        isset($_POST['NoFilm'.$i]) && isset($_POST['Support'.$i])
        && !empty($_POST['NoFilm'.$i]) && !empty($_POST['Support'.$i]))
    {
        $Supports[$_POST['NoFilm'.$i]] =  $_POST['Support'.$i];
        $NoFilms[] = $_POST['NoFilm'.$i];
    }
}
if(empty($Supports)) throw new CoreException(101,'Pas de supports');

/* Trouver les cassettes disponibles */

$data = $BD->cassettesDisponibles($NoFilms,$_POST['Code']);
$films = $data['Films'];
$Exemplaires = $data['Exemplaires'];
unset($data);
if(empty($films)) throw new CoreException(102,'Aucune cassette disponible.');

/* Reserver les cassettes sélectionnées */

$BD->reserverCassettes($Exemplaires, $_POST['Code'], $Outils->date());

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
            <?php if (!isset($film['Exemplaires']['disponible'])): ?>
            <td>
                <span>Non</span>
            </td>
            <?php /* Support non disponible */ ?>
            <?php elseif (!isset($film['Exemplaires']['disponible'][$Supports[$film['NoFilm']]])): ?>
            <?php $nbDispo++;?>
            <td>
                <span><?= $film['Exemplaires']['disponible'][0]; ?></span>
                <SELECT name="<?= 'NoExemplaire'.$i; ?>">
                <?php foreach($film['Exemplaires']['disponible'][0] as $NoExemplaire): ?>
                    <OPTION value="<?= $NoExemplaire;?>"><?= $NoExemplaire;?></OPTION>
                <?php endforeach; ?>
                </SELECT>
            </td>
            <?php /* Support disponible */ ?>
            <?php else: ?>
            <?php $nbDispo++;?>
            <td>
                <span>Oui</span>
                <SELECT name="<?= 'NoExemplaire'.$i; ?>">
                    <?php foreach($film['Exemplaires']['disponible'][$Supports[$film['NoFilm']]] as $NoExemplaire): ?>
                        <OPTION value="<?= $NoExemplaire;?>"><?= $NoExemplaire;?></OPTION>
                    <?php endforeach; ?>
                </SELECT>
            </td>
            <?php endif; ?>
            <input type="hidden" name="NoFilm<?= $i; ?>" value="<?= $film['NoFilm']; ?>"/>
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

