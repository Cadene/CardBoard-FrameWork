<?= $Outils->banniere($include_file); ?>

    <h2>Descriptif d'un film</h2>

    <form action="?p=Descriptif" method="post">
        <input type="text" name="NoFilm" placeholder="Numéro du film"/>
        <input type="submit" value="Rechercher"/>
    </form>

