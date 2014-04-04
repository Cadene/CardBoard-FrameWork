<?php

/* /!\ */
date_default_timezone_set('UTC');

class BD {

    private $bd_name;
    private $server;
    private $login;
    private $psw;

    private $connect;
    private $bd;

    public function __construct($bd_name='VideoExpress',$server='127.0.0.1:3306',$login='root',$psw='root'){
        $this->bd_name = $bd_name;
        $this->server = $server;
        $this->login = $login;
        $this->psw = $psw;
    }

    /**
     * Ouvre la connexion en utf8
     */
    public function connect(){
        $this->connect = new mysqli('127.0.0.1', 'root', 'root', $this->bd_name, '3306');
        if ($this->connect->connect_errno) {
            throw new CoreException(0,'Connexion impossible : ' . $this->connect->connect_error);
        }
        $this->connect->set_charset('utf8');
        return true;
    }

    /**
     * Ferme la connexion si elle existe
     */
    public function close(){
        if(isset($this->connect)){
            $this->connect->close();
        }
    }

    /**
     * Execute une requête SQL
     *
     * @param $sql
     *
     * @return resource
     */
    public function exec($sql){
        $rslt = $this->connect->query($sql);
        if ($this->connect->connect_errno) {
            throw new CoreException(2, 'Requête invalide : ' . $this->connect->error);
        }
        return $rslt;
    }

    public function fetch($rslt){
        return $rslt->fetch_array(MYSQLI_ASSOC);
    }

    public function verifierAbonne($Nom, $Code){
        // Récupération de l'abonné
        $sql = 'SELECT ';
        $sql .= 'a.Code, a.Nom, a.NbCassettes ';
        $sql .= 'FROM ABONNES a ';
        $sql .= 'WHERE a.Code = "'.$Code.'"';
        $sql .= ' LIMIT 1';
        $rslt = $this->exec($sql);
        $abo = $this->fetch($rslt);
        if(empty($abo)){
            throw new CoreException(102,'Le Code n\'existe pas.');
        }
        if($abo['Nom'] != $Nom){
            throw new CoreException(101,'Le Nom associé au Code n\'est pas le même.');
        }
    }


    /**
     * Enlève les réservations expirées
     *
     * @param int $secondes Nombre de secondes avant expiration
     *
     * @return int Nombre de réservation expirée
     */
    public function verifierReservation($secondes=300)
    {
        // Récupération des réservations expirées
        $sql = 'SELECT NoFilm, NoExemplaire ';
        $sql .= 'FROM EMPRES ';
        $sql .= 'WHERE CodeAbonne = "SERVEUR" ';
        $sql .= ' AND UNIX_TIMESTAMP(DateEmpRes) < '.(time() - $secondes);
        $rslt = $this->exec($sql);
        $resa = [];
        while($row = $this->fetch($rslt)){
            $resa[] = $row;
        }
        if(empty($resa)) return 0;

        // Suppression de ces réservations
        foreach($resa as $r){
            $sql = 'UPDATE ';
            $sql .= 'CASSETTES ';
            $sql .= 'SET Statut = "disponible"';
            $sql .= 'WHERE NoFilm = '.$r['NoFilm'];
            $sql .= ' AND NoExemplaire = '.$r['NoExemplaire'];
            $sql .= ';';
            $this->exec($sql);
        }
        foreach($resa as $r){
            $sql = 'DELETE ';
            $sql .= 'FROM EMPRES ';
            $sql .= 'WHERE NoFilm = '.$r['NoFilm'];
            $sql .= ' AND NoExemplaire = '.$r['NoExemplaire'];
            $sql .= ';';
            $this->exec($sql);
        }


        return count($resa);
    }



}
