<?php echo $Outils->banniere($include_file);?>

<h2>Commande de cassettes</h2>

<form action="?p=Commande" method="post">
    <input type="text" name="Nom" placeholder="Nom"/>
    <input type="text" name="Code" placeholder="Code"/>
    <input type="submit" value="Valider"/>
</form>