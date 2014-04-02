<?= $Outils->banniere($include_file); ?>

    <h2>Descriptif d'un film</h2>

    <form action="Descriptif.php" method="post">
        <input type="text" name="numerofilm" placeholder="numerofilm"/>
        <input type="submit" value="Rechercher"/>
    </form>

