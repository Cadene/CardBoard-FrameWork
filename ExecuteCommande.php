<?php

$Cassettes = [];
for($i=1; $i<=3; $i++){
    if(isset($_POST['NoFilm'.$i]) && isset($_POST['NoCassette'.$i])){
        $Cassettes[$_POST['NoFilm'.$i]] =  $_POST['NoCassette'.$i];
    }
}
if(empty($Cassettes)) throw new CoreException();

// TODO maj CASSETTES
// TODO maj ABONNES
// TODO insert EMPRES

?>
<?= $Outils->banniere($include_file); ?>

<h2>Traitement de votre commande</h2>

