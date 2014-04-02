<?php

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
        $this->connect = mysql_connect('127.0.0.1:3306', 'root', 'root');
        if (!$this->connect) {
            throw new CoreException(0,'Connexion impossible : ' . mysql_error());
        }
        mysql_set_charset('utf8'); //!\\
        $this->bd = mysql_select_db($this->bd_name, $this->connect);
        if (!$this->bd) {
            throw new CoreException(0, 'Impossible de sélectionner la base de données : ' . mysql_error());
        }
        return $this->bd;
    }

    /**
     * Ferme la connexion si elle existe
     */
    public function close(){
        if(isset($this->connect)){
            mysql_close($this->connect);
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
        $rslt = mysql_query($sql);
        if (!$rslt) {
            throw new CoreException(0, 'Requête invalide : ' . mysql_error());
        }
        return $rslt;
    }

    public function fetch($rslt){
        return mysql_fetch_assoc($rslt);
    }




}
