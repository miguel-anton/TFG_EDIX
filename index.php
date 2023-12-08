<html>
    <?php
            
        $accion = 'listarTodas';
    
    ?>
    <head>
        <title>Comandas</title>
        <meta chatset="UTF-8">
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

          
         <!-- Ahora, mostramos los pedidos de WooCommerce -->
        
         <?php
    
        $daoWooCommercePedidos = new DaoWooCommercePedidos();
        $DaoWooCommerceEnvios = new DaoWooComerceEnvio();
        $pedidosWooCommerce = $daoWooCommercePedidos->obtenerPedidosWooCommerce();
        $pedidosdeldia= $daoWooCommercePedidos->obtenerPedidosWooCommerce()
        
     
        
        ?>

        
         <div id="expandir-container">
        <h1> Pedidos de hoy</h1>
        <div>
            <button id="expandirHoy" > Expandir pedidos </button>
            <button id="contraerHoy" > Contraer pedidos </button>

        </div>
        </div>
        <?php 
            // Eliminar pedidos que no son del día (comentar por ahora)
           foreach ($pedidosdeldia as $key => $order) {

            $fechaPedido = new DateTime($order['post_date']);
            $fechaHoy = new DateTime();
            if ($fechaPedido->format('Y-m-d') !== $fechaHoy->format('Y-m-d')) {
                unset($pedidosdeldia[$key]);
            }
        };
  
        ?>

        <?php foreach ($pedidosdeldia as $order) : ?>
            <div class="pedido" id="order">
                <table class="table table-striped table-bordered table-borderless">
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th >Nombre</th>
                        <th>Cantidad Total</th>
                        <th>Total</th>
                        <th>Tipo de pedido</th>
                        <th colspan="6">
                            <button class="mostrar-detalles" data-target="<?php echo $order['order_id']; ?>">
                                Mostrar más
                            </button>

                            <button class="envio-detalles" id="envio-detalles" data-target="<?php echo $order['order_id']; ?>">
                                info envio
                            </button>
                        </th>
                        

                    </tr>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['post_date']; ?></td>
                        <td id="nombre"><?php echo $order['customer_name']; ?></td>
                        <td>
                            <?php echo $daoWooCommercePedidos->obtenerDetallesPrecioPedidos($order['order_id'])[0]['total_quantity']; ?>
                        </td>
                        <td>
                            <?php echo $daoWooCommercePedidos->obtenerDetallesPrecioPedidos($order['order_id'])[0]['total_line_total']; ?>
                        </td>
                        <td id="tipoEnvio">
                            <!--Cambié esta línea para mostrar el tipo de pedido -->
                            <?php echo $DaoWooCommerceEnvios->obtenerTipoEnvioPorPedido($order['order_id'])[0]['tipo_pedido']; ?>
                        </td>
                        <td>
                        <button class="mandar-a-cocina" data-order-id="<?php echo $order['order_id']; ?>">
                          Mandar a Cocina
                        </button>
                        </td>
                    </tr>
                    
                    <!-- Nueva fila para la información de envío -->
                    
                </table>
                <!-- Subtabla con información detallada de cada producto -->
                <div class="secundario" id="secundario">
                <table class="subtabla" name="subtabla" id="detalles-<?php echo $order['order_id']; ?>">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </tr>
                    <?php
                    // Obtener información detallada de cada producto
                    $productosDetalles = $daoWooCommercePedidos->obtenerDetallesProductosPedido($order['order_id']);
                    foreach ($productosDetalles as $productoDetalle) :
                    ?>
                        <tr>
                            <td><?php echo $productoDetalle['order_item_name']; ?></td>
                            <td><?php echo $productoDetalle['quantity']; ?></td>
                            <td><?php echo $productoDetalle['line_total']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>


                <!-- Subtabla con comentarios de cada pedido -->
                <table class="comentarios" name="comentarios" id="comentarios-<?php echo $order['order_id']; ?>">
                    <tr>
                        <th>Comentarios</th>
                    </tr>
                    <tr>
                        <td><?php echo $order['comments']; ?></td>
                    </tr>
                </table>


                <table class="envios" name="envios" id="envio-<?php echo $order['order_id']; ?>">
                    <tr>
                        <th>NOMBRE</th>
                        <th>DIRECCION</th>
                        <th>POBLACION</th>
                        <th>CODIGO POSTAL</th>
                        <th>TELEFONO</th>
                        <th>EMAIL</th>
                    </tr>
                    <?php
                

                    // Obtener información detallada de cada producto
                    $productosEnvios = $DaoWooCommerceEnvios->obtenerInfoFacturacionPedido($order['order_id']);
                    foreach ($productosEnvios as $productosEnvio) :
                    ?>
                        <tr>
                            <td><?php echo $productosEnvio['_billing_first_name']; ?></td>
                            <td><?php echo $productosEnvio['_billing_address_1']; ?></td>
                            <td><?php echo $productosEnvio['_billing_city']; ?></td>
                            <td><?php echo $productosEnvio['_billing_postcode']; ?></td>
                            <td><?php echo $productosEnvio['_billing_phone']; ?></td>
                            <td><?php echo $productosEnvio['_billing_email']; ?></td>

                        
                        </tr>
                    <?php endforeach; ?>
                </table>
                </div>
            </div>
        
        <?php endforeach; ?>
         
        
        <div id="Woocommerce">
        <!-- Mostrar los pedidos de WooCommerce -->
        <div id="expandir-container">
        <h1> Pedidos online</h1>
        <div>
            <button id="expandir" > Expandir pedidos </button>
            <button id="contraer" > Contraer pedidos </button>

        </div>
        </div>
        <?php foreach ($pedidosWooCommerce as $pedido) : ?>
            <div class="pedido">
                <table class="table table-striped table-bordered table-borderless">
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th >Nombre</th>
                        <th>Cantidad Total</th>
                        <th>Total</th>
                        <th>Tipo de pedido</th>
                        <th colspan="6">
                            <button class="mostrar-detalles" data-target="<?php echo $pedido['order_id']; ?>">
                                Mostrar más
                            </button>

                            <button class="envio-detalles" id="envio-detalles" data-target="<?php echo $pedido['order_id']; ?>">
                                info envio
                            </button>
                        </th>
                        

                    </tr>
                    <tr>
                        <td><?php echo $pedido['order_id']; ?></td>
                        <td><?php echo $pedido['post_date']; ?></td>
                        <td id="nombre"><?php echo $pedido['customer_name']; ?></td>
                        <td>
                            <?php echo $daoWooCommercePedidos->obtenerDetallesPrecioPedidos($pedido['order_id'])[0]['total_quantity']; ?>
                        </td>
                        <td>
                            <?php echo $daoWooCommercePedidos->obtenerDetallesPrecioPedidos($pedido['order_id'])[0]['total_line_total']; ?>
                        </td>
                        <td id="tipoEnvio">
                            <!--Cambié esta línea para mostrar el tipo de pedido -->
                            <?php echo $DaoWooCommerceEnvios->obtenerTipoEnvioPorPedido($pedido['order_id'])[0]['tipo_pedido']; ?>
                        </td>
                        <td></td>
                    </tr>
                    
                    <!-- Nueva fila para la información de envío -->
                    
                </table>
                <!-- Subtabla con información detallada de cada producto -->
                <div class="secundario" id="secundario">
                <table class="subtabla" id="detalles-<?php echo $pedido['order_id']; ?>">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </tr>
                    <?php
                    // Obtener información detallada de cada producto
                    $productosDetalles = $daoWooCommercePedidos->obtenerDetallesProductosPedido($pedido['order_id']);
                    foreach ($productosDetalles as $productoDetalle) :
                    ?>
                        <tr>
                            <td><?php echo $productoDetalle['order_item_name']; ?></td>
                            <td><?php echo $productoDetalle['quantity']; ?></td>
                            <td><?php echo $productoDetalle['line_total']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>


                <!-- Subtabla con comentarios de cada pedido -->
                <table class="comentarios" id="comentarios-<?php echo $pedido['order_id']; ?>">
                    <tr>
                        <th>Comentarios</th>
                    </tr>
                    <tr>
                        <td><?php echo $pedido['comments']; ?></td>
                    </tr>
                </table>


                <table class="envios" id="envio-<?php echo $pedido['order_id']; ?>">
                    <tr>
                        <th>NOMBRE</th>
                        <th>DIRECCION</th>
                        <th>POBLACION</th>
                        <th>CODIGO POSTAL</th>
                        <th>TELEFONO</th>
                        <th>EMAIL</th>
                    </tr>
                    <?php
                

                    // Obtener información detallada de cada producto
                    $productosEnvios = $DaoWooCommerceEnvios->obtenerInfoFacturacionPedido($pedido['order_id']);
                    foreach ($productosEnvios as $productosEnvio) :
                    ?>
                        <tr>
                            <td><?php echo $productosEnvio['_billing_first_name']; ?></td>
                            <td><?php echo $productosEnvio['_billing_address_1']; ?></td>
                            <td><?php echo $productosEnvio['_billing_city']; ?></td>
                            <td><?php echo $productosEnvio['_billing_postcode']; ?></td>
                            <td><?php echo $productosEnvio['_billing_phone']; ?></td>
                            <td><?php echo $productosEnvio['_billing_email']; ?></td>

                        
                        </tr>
                    <?php endforeach; ?>
                </table>
                </div>
            </div>
        
        <?php endforeach; ?>
        </div>
        </div>

    </body>
</html>