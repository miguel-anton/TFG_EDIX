<?php

class Gestor{

    private $usuario;
    private $password;
    private $rol;

    public function __get($propiedad){
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor){
        $this->$propiedad = $valor;
    }

}

?>