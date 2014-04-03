<?php echo $Outils->banniere($include_file);?>

<h2>Commande de cassettes</h2>

<form action="Commande.php" method="post">
    <input type="text" name="Nom" placeholder="Nom"/>
    <input type="password" name="Pass" placeholder="Mot de passe"/>
    <input type="submit" value="Valider"/>
</form>