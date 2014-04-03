<?= $Outils->admin_banniere();?>

<h2>Accueil Retour</h2>

<form action="Retour.php" method="post">
    <input type="text" name="NoFilm" placeholder="Numéro du film"/>
    <input type="text" name="NoExemplaire" placeholder="Numéro de l'exemplaire"/>
    <input type="submit" value="Valider"/>
</form>