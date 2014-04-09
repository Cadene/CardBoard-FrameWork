<?php echo $Outils->banniere($include_file);?>

<h2>Liste des cassettes détenues</h2>

<form action="?p=Detenues" method="post">
    <input type="text" name="Nom" placeholder="Nom"/>
    <input type="text" name="Code" placeholder="Numéro d'abonné"/>
    <input type="submit" value="Valider"/>
</form>