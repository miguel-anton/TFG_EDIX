<?php

require_once(__DIR__ . "/../Entidades/Gestor.php");
require_once("Conexion.php");

class DaoGestores extends Conexion{


    //comprueba si el suaurio y contraseña existen, devolverá un objeto Gestor o un False
    public function comprobarUsuario(Gestor $gestor){

        $consulta = "SELECT rol FROM gestores WHERE nombre=:nombre AND password=:password";

        $parametros = array(
            ":nombre" => $gestor->__get("nombre"),
            ":password" => $gestor->__get("password")
        );

        $this->consultaDatos($consulta, $parametros);

        if($this->filas){

            foreach($this->filas as $fila){
                $gestor->__set('rol', $fila['rol']);
            }
    
            return $gestor;
        
        }else{

            return false;
        }

    }
}

?>