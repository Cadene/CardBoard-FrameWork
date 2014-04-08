<?php

$BD->exec('UPDATE CASSETTES SET Statut = "disponible"');
$BD->exec('TRUNCATE EMPRES');
$BD->exec('UPDATE ABONNES SET NbCassettes = 0');