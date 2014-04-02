<?php echo $Outils->banniere();?>

<h2>Commande de cassettes</h2>

<form action="Detenues.php" type="post">
    <input type="text" name="nom" placeholder="Nom"/>
    <input type="password" name="pass" placeholder="Mot de passe"/>
    <input type="submit" value="Valider"/>
</form>