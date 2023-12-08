<?php

    require_once("../Modelo/DAOs/DaoGestores.php");

    //Se crea una instancia del dao
    $daoGestores = new DaoGestores;

    $datosValidos = validarDatos($_POST['username'], $_POST['password']);

    if($datosValidos){

        //Se crea el objeto Gestor
        $gestor = new Gestor();
        $gestor->__set("nombre", $_POST['username']);

        $saltIni="$6&ª1";     //Añadimos una cadena al principio
        $saltFin="=)/?&%";    //Añadimos una cadena al final
        $pw = $saltIni . $_POST['password'] . $saltFin;
        $hash = sha1($pw);

        $gestor->__set("password", $hash);

        $gestor = $daoGestores->comprobarUsuario($gestor);

        if($gestor != false){

            session_start();
            $_SESSION['usuario'] = $_POST['username'];
            header('location:../index.php');  

        }else{

            header('location:../login-gestores.php?error=nocoincide');  

        }

    }else{

        header('location:../login-gestores.php?error=vacio');  

    }


    function validarDatos($usuario, $password){

        if(!empty($usuario) && !empty($password)){

            return true;

        }else{

            return false;

        }

    }

?>