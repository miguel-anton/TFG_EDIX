<head>
    <title>Cerrar Sesión</title>
    <meta charset="UTF-8">
</head>
<body>
    <?php
        include ("Controlador/controlarSesion.php");
        session_unset();
        session_destroy();
    ?>
    <h1>Control de sesiones</h1>
    <p>Sesion cerrada</p>
    <p><a href="index.php">Volver al inicio</a></p>
</body>
</html>