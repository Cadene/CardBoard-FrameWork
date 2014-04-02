<?php

class CoreException extends Exception {

    private $id;
    private $msg;

    public function __construct($id=null,$msg=null){
        if($id === null)
            $id = -1;
        if($msg === null)
            $msg = 'Veuillez contacter l\'administrateur.';
        $this->id = $id;
        $this->msg = $msg;
    }

    public function getMsg(){
        return $this->msg;
    }

    public function getId(){
        return $this->id;
    }

    public function toHTML(){
        return '<div id="error">'.$this->toString().'</div>';
    }

    public function toString(){
        return 'CoreException {id:'.$this->id.', msg:'.$this->msg.'}';
    }

}