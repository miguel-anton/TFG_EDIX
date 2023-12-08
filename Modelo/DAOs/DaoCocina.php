<?php

require_once("Conexion.php");


class DaoCocina extends Conexion{
    public function sacarComanda() {
        $consulta = "
        SELECT
        orders.ID AS order_id,
        orders.post_date AS post_date,
        orders.post_excerpt AS comments,
        orders.post_status,
        GROUP_CONCAT(order_items.order_item_name SEPARATOR ', ') AS productos,
        SUM(order_itemmeta_qty.meta_value) AS total_quantity,
        ROUND(SUM(order_itemmeta_total.meta_value),2) AS total_line_total,
        customer_meta.meta_value AS customer_name,
        mesa_meta.meta_value AS mesa
    FROM
        wp_posts AS orders
        LEFT JOIN wp_woocommerce_order_items AS order_items ON orders.ID = order_items.order_id
        LEFT JOIN (
            SELECT order_item_id, meta_value
            FROM wp_woocommerce_order_itemmeta
            WHERE meta_key = '_qty'
        ) AS order_itemmeta_qty ON order_items.order_item_id = order_itemmeta_qty.order_item_id
        LEFT JOIN (
            SELECT order_item_id, meta_value
            FROM wp_woocommerce_order_itemmeta
            WHERE meta_key = '_line_total'
        ) AS order_itemmeta_total ON order_items.order_item_id = order_itemmeta_total.order_item_id
        LEFT JOIN wp_postmeta AS customer_meta ON orders.ID = customer_meta.post_id AND customer_meta.meta_key = '_billing_first_name'
        LEFT JOIN wp_postmeta AS mesa_meta ON orders.ID = mesa_meta.post_id AND mesa_meta.meta_key = '_billing_wooccm9'
    WHERE
        orders.post_type = 'shop_order'
        AND orders.ID IN (
            SELECT order_id
            FROM wp_woocommerce_order_items
            WHERE order_item_name = 'comer en local'
        )
    GROUP BY
        orders.ID
    ORDER BY
        orders.post_date DESC;
    
    
        ";
    
        $parametros = array();
        $this->consultaDatos($consulta, $parametros);
    
        return $this->filas;
    }


    public function detallesComanda($order_id) {
        $consulta="
        SELECT
            order_items.order_item_name AS producto,
            COALESCE(order_itemmeta_qty.meta_value, 0) AS cantidad,
            COALESCE(order_itemmeta_total.meta_value, 0) AS precio_unitario
        FROM
            wp_woocommerce_order_items AS order_items
            LEFT JOIN wp_woocommerce_order_itemmeta AS order_itemmeta_qty ON order_items.order_item_id = order_itemmeta_qty.order_item_id AND order_itemmeta_qty.meta_key = '_qty'
            LEFT JOIN wp_woocommerce_order_itemmeta AS order_itemmeta_total ON order_items.order_item_id = order_itemmeta_total.order_item_id AND order_itemmeta_total.meta_key = '_line_total'
        WHERE
            order_items.order_id = :order_id
            AND order_items.order_item_name != 'comer en local';  -- Cambiado para excluir 'comer en local'
        ";
    
        $parametros = array(':order_id' => $order_id);
        $this->consultaDatos($consulta, $parametros);
    
        return $this->filas;
    }
    
    
    
    
}

?>