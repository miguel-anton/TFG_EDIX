
<?php
$accion = 'listarTodas';
include('Modelo/DAOs/DaoCocina.php');

$daoCocina = new DaoCocina();
$sacarComanda = $daoCocina->sacarComanda();
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
        <h1>Comandas para preparar</h1>
        <?php foreach ($sacarComanda as $pedido): ?>
        <div class="pedido">       
            <table class="table table-striped table-bordered table-borderless">
                <tr>
                    <th>Nombre</th>
                    <th>Hora</th>
                    <th>Mesa</th>
                    <th>Cantidad</th>
                    <th>Precio</th>

                </tr>
                
                <tr>
                    <td><?php echo $pedido['customer_name']; ?></td>
                    <td><?php echo $pedido['post_date']; ?></td>
                    <td><?php echo $pedido['mesa']; ?></td>
                    <td><?php echo $pedido['total_quantity']; ?></td>
                    <td><?php echo $pedido['total_line_total']; ?></td>

                </tr>
            
            </table>

            <div class="secundario">
                <?php 
                $detallesComanda = $daoCocina->detallesComanda($pedido['order_id']);
                if (!empty($detallesComanda)): ?>
                    <table class="subtablaC" >
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                        </tr>
                        <?php foreach ($detallesComanda as $cocinaDetalle): ?>
                            <tr>
                                <td><?php echo $cocinaDetalle['producto']; ?></td>
                                <td><?php echo $cocinaDetalle['cantidad']; ?></td>
                                <td><?php echo $cocinaDetalle['precio_unitario']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No hay detalles disponibles para esta comanda.</p>
                <?php endif; ?> 

                <table class="comentariosC" >
                    <tr>
                        <th>Comentarios</th>
                    </tr>
                    <tr>
                        <td><?php echo $pedido['comments']; ?></td>
                    </tr>
                </table>        

            </div>
        </div>
        <?php endforeach; ?> 
    </div>
   
</body>
</html>

