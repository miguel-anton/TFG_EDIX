<?php
$accion = 'listarTodas';
include('Modelo/DAOs/DaoReservas.php'); // Asegúrate de poner la ruta correcta

$DaoReservas = new DaoReservas();
$obtenerReservas = $DaoReservas->obtenerReservas();
$obtenerReservasDeHoy = $DaoReservas->obtenerReservasDeHoy();
?>

<html>
<head>
    <title>Comandas</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="util/estilos.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <script src="util/funciones.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <?php include('menu.php'); ?>
    <div class="contenido wrapper">
    <h1>Reservas de hoy</h1>
        <table class="table table-striped table-bordered table-borderless">
            <tr>
                <th>Hora</th>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Comensales</th>
                <th>Telefono cliente</th>
                <th>correo cliente</th>

                
            </tr>
            <?php foreach ($obtenerReservasDeHoy as $reservasHoy): ?>
                <tr>
                    <td><?php echo $reservasHoy['hora_inicio']; ?></td>
                    <td><?php echo $reservasHoy['Fecha']; ?></td>
                    <td><?php echo $reservasHoy['nombre_cliente']; ?></td>
                    <td><?php echo $reservasHoy['Num_comensales']; ?></td>
                    <td><?php echo $reservasHoy['telefono_cliente']; ?></td>
                    <td><?php echo $reservasHoy['correo_cliente']; ?></td>


                </tr>
            <?php endforeach; ?> 
        </table>
        <h1>Listado de reservas</h1>
        <table class="table table-striped table-bordered table-borderless">
            <tr>
                <th>Hora</th>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Comensales</th>
                <th>Telefono cliente</th>
                <th>correo cliente</th>

                
            </tr>
            <?php foreach ($obtenerReservas as $reserva): ?>
                <tr>
                    <td><?php echo $reserva['hora_inicio']; ?></td>
                    <td><?php echo $reserva['Fecha']; ?></td>
                    <td><?php echo $reserva['nombre_cliente']; ?></td>
                    <td><?php echo $reserva['Num_comensales']; ?></td>
                    <td><?php echo $reserva['telefono_cliente']; ?></td>
                    <td><?php echo $reserva['correo_cliente']; ?></td>


                </tr>
            <?php endforeach; ?> 
        </table>
    </div>
</body>
</html>
