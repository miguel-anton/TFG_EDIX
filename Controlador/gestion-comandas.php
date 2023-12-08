<?php
    include ("controlarSesion.php");

    include ("Modelo/DAOs/DaoWooCommercePedidos.php");
   
    
    $daoWooCommercePedidos = new DaoWooCommercePedidos();
    
    if($accion == 'listarTodas'){

        $daoComandas->listarTodo();
         // Obtener los pedidos de WooCommerce
         $pedidosWooCommerce = $daoWooCommercePedidos->obtenerPedidosWooCommerce();

    }

   
    
    
?>