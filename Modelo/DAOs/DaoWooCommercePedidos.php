<?php

require_once("Conexion.php");

class DaoWooCommercePedidos extends Conexion {

    public function obtenerPedidosWooCommerce() {
        $consulta = "
        SELECT
        orders.ID AS order_id,
        orders.post_date,
        customer_meta.meta_value AS customer_name,
        orders.post_excerpt AS comments
    FROM
        wp_posts AS orders
        LEFT JOIN wp_postmeta AS customer_meta ON orders.ID = customer_meta.post_id AND customer_meta.meta_key = '_billing_first_name'
        -- Subconsulta para seleccionar solo los pedidos a domicilio y en local.
        JOIN (
            SELECT DISTINCT order_id
            FROM wp_woocommerce_order_items
            WHERE order_item_name IN ('A domicilio', 'Recogida local')
        ) AS order_items_filter ON orders.ID = order_items_filter.order_id
    WHERE
        orders.post_type = 'shop_order'
    ORDER BY
        orders.post_date DESC;
    ";
    
        $parametros = array();
        $this->consultaDatos($consulta, $parametros);
    
        return $this->filas;
    }
    

    public function obtenerDetallesProductosPedido($order_id) {
        $consulta = "
        SELECT
            order_items.order_item_name,
            order_itemmeta_qty.meta_value AS quantity,
            order_itemmeta_total.meta_value AS line_total
        FROM
            wp_woocommerce_order_items AS order_items
            LEFT JOIN wp_woocommerce_order_itemmeta AS order_itemmeta_qty ON order_items.order_item_id = order_itemmeta_qty.order_item_id AND order_itemmeta_qty.meta_key = '_qty'
            LEFT JOIN wp_woocommerce_order_itemmeta AS order_itemmeta_total ON order_items.order_item_id = order_itemmeta_total.order_item_id AND order_itemmeta_total.meta_key = '_line_total'
        WHERE
            order_items.order_id = :order_id
            AND order_itemmeta_qty.meta_value IS NOT NULL  -- Excluir valores nulos de cantidad
            AND order_itemmeta_total.meta_value IS NOT NULL  -- Excluir valores nulos de total de línea

        ";
    
        $parametros = array(":order_id" => $order_id);
        $this->consultaDatos($consulta, $parametros);
    
        return $this->filas;
    }

    public function obtenerDetallesPrecioPedidos($order_id) {
        $consulta = "
        SELECT
            COALESCE(SUM(order_items_qty.meta_value), 0) AS total_quantity,
            ROUND(COALESCE(SUM(order_items_total.meta_value), 0),2) AS total_line_total
        FROM
            wp_woocommerce_order_items AS order_items
            LEFT JOIN wp_woocommerce_order_itemmeta AS order_items_qty ON order_items.order_item_id = order_items_qty.order_item_id AND order_items_qty.meta_key = '_qty'
            LEFT JOIN wp_woocommerce_order_itemmeta AS order_items_total ON order_items.order_item_id = order_items_total.order_item_id AND order_items_total.meta_key = '_line_total'
        WHERE
            order_items.order_id = :order_id
    ";

    $parametros = array(":order_id" => $order_id);
    $this->consultaDatos($consulta, $parametros);

    return $this->filas;
    }
    
    
    
}

class DaoWooComerceEnvio extends Conexion {
    public function obtenerInfoFacturacionPedido($order_id) {
        $consulta = "
            SELECT
                p.ID as order_id,
                p.post_date,
                MAX(CASE WHEN pm.meta_key = '_billing_first_name' THEN pm.meta_value END) as _billing_first_name,
                MAX(CASE WHEN pm.meta_key = '_billing_last_name' THEN pm.meta_value END) as _billing_last_name,
                MAX(CASE WHEN pm.meta_key = '_billing_address_1' THEN pm.meta_value END) as _billing_address_1,
                MAX(CASE WHEN pm.meta_key = '_billing_city' THEN pm.meta_value END) as _billing_city,
                MAX(CASE WHEN pm.meta_key = '_billing_state' THEN pm.meta_value END) as _billing_state,
                MAX(CASE WHEN pm.meta_key = '_billing_postcode' THEN pm.meta_value END) as _billing_postcode,
                MAX(CASE WHEN pm.meta_key = '_billing_phone' THEN pm.meta_value END) as _billing_phone,
                MAX(CASE WHEN pm.meta_key = '_billing_email' THEN pm.meta_value END) as _billing_email
            FROM
                wp_posts p
            LEFT JOIN
                wp_postmeta pm ON p.ID = pm.post_id
            WHERE
                p.post_type = 'shop_order' AND
                p.ID = :order_id
            GROUP BY
                p.ID;
        ";
    
        $parametros = array(":order_id" => $order_id);
        $this->consultaDatos($consulta, $parametros);
    
        return $this->filas;
    }
    
    public function obtenerTipoEnvioPorPedido($order_id) {
        $consulta = "
            SELECT
                order_id,
                order_item_name AS tipo_pedido
            FROM
                wp_woocommerce_order_items
            WHERE
                order_item_type = 'shipping'
                AND order_id = :order_id
        ";
        $parametros = array(":order_id" => $order_id);
        $this->consultaDatos($consulta, $parametros);
    
        return $this->filas;
    }
    
    
}


?>