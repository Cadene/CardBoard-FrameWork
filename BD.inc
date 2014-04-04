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
        if ($this->connect->error) {
            throw new CoreException(2, 'Requête invalide : ' . $this->connect->error);
        }
        return $rslt;
    }

    public function fetch($rslt){
        return $rslt->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * Trouve distinctement un seul champ d'une table
     *
     * @param $field
     * @param $table
     * @return array
     */
    function findOne($field,$table){
        $array = [];
        $rslt = $this->exec('SELECT DISTINCT '.$field.' FROM '.$table);
        while ($row = $this->fetch($rslt)){
            $array[] = $row[$field];
        }
        return $array;
    }

    /**
     * Vérification des identifiants de l'abonné
     *
     * @param $Nom
     * @param $Code
     * @throws CoreException
     */
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
    public function verifierReservations($secondes=300)
    {
        return $this->enleverReservationsCond(
            'UNIX_TIMESTAMP(DateEmpRes) < '.(time() - $secondes)
        );
    }

    /**
     * Enlève les réservations d'un utilisateur
     *
     * @param $Code
     * @return int
     */
    public function enleverReservations($Code)
    {
        return $this->enleverReservationsCond(
            'e.CodeAbonne = "'.$Code.'"'
        );
    }

    public function enleverReservationsCond($condition)
    {
        // Récupération des réservations expirées
        $sql = 'SELECT e.NoFilm, e.NoExemplaire ';
        $sql .= 'FROM EMPRES e, CASSETTES c ';
        $sql .= 'WHERE '.$condition;
        $sql .= ' AND e.NoFilm = c.NoFilm AND e.NoExemplaire = c.NoExemplaire';
        $sql .= ' AND c.Statut = "reservee"';
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

    /**
     * Selection et formatage des cassettes disponibles pour l'utilisateur
     *
     * @param $NoFilms
     * @param $Code
     * @return array
     */
    public function cassettesDisponibles($NoFilms,$Code)
    {
        /* Disponibles ou Empruntee*/
        $sql = 'SELECT ';
        $sql .= 'f.NoFilm, f.Titre, f.Realisateur, ';
        $sql .= 'c.NoExemplaire, c.Support, c.Statut ';
        $sql .= 'FROM FILMS f, CASSETTES c ';
        $sql .= 'WHERE c.NoFilm = f.NoFilm';
        $sql .= ' AND (c.Statut = "disponible" OR c.Statut = "empruntee")';
        $sql .= ' AND (';
        $k=0;
        foreach($NoFilms as $NoFilm){
            $sql .= ($k==0 ? '' : ' OR ') . 'f.NoFilm = '.$NoFilm;
            $k++;
        }
        $sql .= ' )';
        $disponibles = $this->exec($sql);

        /* Reservées */
        $sql = 'SELECT ';
        $sql .= 'f.NoFilm, f.Titre, f.Realisateur, ';
        $sql .= 'c.NoExemplaire, c.Support, c.Statut ';
        $sql .= 'FROM FILMS f, CASSETTES c, EMPRES e ';
        $sql .= 'WHERE c.NoFilm = f.NoFilm';
        $sql .= ' AND c.Statut = "reservee"';
        $sql .= ' AND e.NoFilm = f.NoFilm AND e.NoExemplaire = c.NoExemplaire';
        $sql .= ' AND e.CodeAbonne = "'.$Code.'"';
        $sql .= ' AND (';
        $k=0;
        foreach($NoFilms as $NoFilm){
            $sql .= ($k==0 ? '' : ' OR ') . 'f.NoFilm = '.$NoFilm;
            $k++;
        }
        $sql .= ' )';
        $reservees = $this->exec($sql);

        $films = [];
        $exemplaires = [];
        foreach([$disponibles,$reservees] as $rslt)
        {
            while($rows = $this->fetch($rslt))
            {
                if(!isset($films[$rows['NoFilm']]))
                {
                    $films[$rows['NoFilm']]['NoFilm'] = $rows['NoFilm'];
                    $films[$rows['NoFilm']]['Titre'] = $rows['Titre'];
                    $films[$rows['NoFilm']]['Realisateur'] = $rows['Realisateur'];
                }
                if($rows['Statut'] == "reservee")
                    $rows['Statut'] = "disponible";
                $films[$rows['NoFilm']]['Exemplaires'][$rows['Statut']][$rows['Support']][] = $rows['NoExemplaire'];
                $exemplaires[$rows['NoFilm']][] = $rows['NoExemplaire'];
            }
        }

        $data['Films'] = $films;
        $data['Exemplaires'] = $exemplaires;
        return $data;
    }

    public function emprunterCassettes($Exemplaires, $Code, $date)
    {
        foreach ($Exemplaires as $NoFilm=>$NoExemplaire) {
            $sql = 'UPDATE ';
            $sql .= 'CASSETTES ';
            $sql .= 'SET Statut = "empruntee" ';
            $sql .= 'WHERE NoFilm = '.$NoFilm.' AND '.$NoExemplaire;
            $this->exec($sql);
        }

        /* Mise à jour du nombre d'emprunt de l'abonné */
        $sql = 'UPDATE ';
        $sql .= 'ABONNES ';
        $sql .= 'SET NbCassettes = NbCassettes + 1 ';
        $sql .= 'WHERE Code = "'.$_POST['Code'].'"';
        $this->exec($sql);

        /* Création des emprunts */
        $sql = 'INSERT ';
        $sql .= 'INTO EMPRES ';
        $sql .= '(NoFilm, NoExemplaire, CodeAbonne, DateEmpRes) ';
        $sql .= 'VALUES ';
        $k=0;
        foreach($Exemplaires as $NoFilm=>$NoExemplaire){
            $sql .= ($k==0 ? '' : ',') . '('.$NoFilm.','.$NoExemplaire.',"'.$Code.'","'.$date.'")';
            $k++;
        }
        $this->exec($sql);
    }

    public function reserverCassettes($Exemplaires, $Code, $date)
    {
        foreach ($Exemplaires as $NoFilm=>$NoExemplaire) {
            foreach($NoExemplaire as $NoE){
                $sql = 'UPDATE ';
                $sql .= 'CASSETTES ';
                $sql .= 'SET Statut = "reservee" ';
                $sql .= 'WHERE NoFilm = '.$NoFilm.' AND '.$NoE;
                $this->exec($sql);
            }
        }
        /* Création des emprunts */
        $sql = 'INSERT ';
        $sql .= 'INTO EMPRES ';
        $sql .= '(NoFilm, NoExemplaire, CodeAbonne, DateEmpRes) ';
        $sql .= 'VALUES ';
        $k=0;
        foreach($Exemplaires as $NoFilm=>$NoExemplaire){
            foreach($NoExemplaire as $NoE){
                $sql .= ($k==0 ? '' : ',') . '('.$NoFilm.','.$NoE.',"'.$Code.'","'.$date.'")';
                $k++;
            }
        }
        $this->exec($sql);

    }


}
