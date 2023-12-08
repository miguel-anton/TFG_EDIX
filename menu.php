
<?php include ("Controlador/gestion-comandas.php"); ?>
<header>
    <section class="wrapper">
        <nav>
            <ul>
                <li><a href="reservas.php"> Reservas</a></li>
                <li><a href="index.php">Pedidos online</a></li>
                <li><a href="cocina.php"> Comandas</a></li>
            </ul>
        </nav>
        

        <div class="sesion">
            <p>Se ha autetificado como: <?php echo $_SESSION['usuario'];?><p>
            <p><a href="cerrarSesion.php?cerrar">Cerrar sesión</a></p>
        </div>
    </section>
</header>