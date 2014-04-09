<?php

if(!isset($_COOKIE['selection']) || $_COOKIE['selection'][0] <= 0)
{
    echo '<div>Aucun film sélectionné</div>';
}
else
{
    $nb_films = $_COOKIE['selection'][0];
    for($i=1; $i<=$nb_films; $i++){
        $films[$i]['NoFilm'] = $_COOKIE['selection'][$i];
    }

    $sql .= 'SELECT ';
    $sql .= 'f.Titre, f.Realisateur  ';
    $sql .= 'FROM FILMS f ';
    $sql .= 'WHERE ';
    $k=0;
    foreach($films as $film){
        $sql .= ($k == 0 ? '' : ' OR ') . 'f.NoFilm = '.$film['NoFilm'];
        $k++;
    }

    $rslt = $BD->exec($sql);
    $i=1;
    while($row = $BD->fetch($rslt)){
        $films[$i]['Titre'] = $row['Titre'];
        $films[$i]['Realisateur'] = $row['Realisateur'];
        $i++;
    }

?>
<form action="?p=<?= $include_file;?>&pp=SuppSelection" method="post">
    <table>
        <tr>
            <td>NoFilm</td>
            <td>Titre</td>
            <td>Realisateur</td>
            <td>Supprimer</td>
        </tr>
        <?php $i=0;?>
        <?php foreach($films as $film): ?>
            <tr>
                <td><?= $film['NoFilm'];?></td>
                <td><?= $film['Titre'];?></td>
                <td><?= $film['Realisateur'];?></td>
                <td><input type="checkbox" name="NoFilm_<?= $i;?>" value="<?= $film['NoFilm'];?>"/></td>
            </tr>
            <?php $i++;?>
        <?php endforeach;?>
    </table>
    <input type="submit" value="Supprimer"/>
    <input type="hidden" name="nb_films" value="<?= $i;?>"/>
</form>

<?php
}
