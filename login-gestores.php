<?php

if(isset($_GET['error'])){

    if($_GET['error'] == 'vacio'){

        $error = 'Rellena todos los campos';

    }else{

        $error = 'El usuario y la contraseña no coinciden';

    }
}

?>

<html>

<head>
    <meta chatset="UTF-8">
    <link rel="stylesheet" type="text/css" href="util/estilos.css">
</head>

<body>
    <form action="Controlador/validform-login.php" method="POST">
        <div id="login-box">
            <h1>Iniciar sesión</h1> 

            <div class="form">
                <div class="item"> 
                    <i class="fa fa-user-circle" aria-hidden="true"></i>
                    <input type="text" placeholder="Usuario" name="username"> 
                </div>
                <br>
                <div class="item"> 
                    <i class="fa fa-key" aria-hidden="true"></i>
                    <input type="password" placeholder="Contraseña" name="password"> 
                </div>
            </div>

            <?php
                if(isset($error)){
                    print '<p id="errorLogin" style="color: white;"><b>' . $error . '</b></p>';
                }
            ?>
            
            <button type="submit">Entrar</button>
        </div>
    </form>
</body>

</html>