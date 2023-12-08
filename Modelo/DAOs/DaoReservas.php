<?php
require_once("Conexion.php");

class DaoReservas extends Conexion {

    public function obtenerReservas() {
        $consulta ="SELECT * FROM Reserva ORDER BY ID DESC ";
        $parametros = array();
        $this->consultaDatos($consulta, $parametros);

        return $this->filas;
    }
    public function obtenerReservasDeHoy() {
        // Obtener la fecha de hoy en el formato de tu base de datos (puede necesitar ajustes)
        $fecha_hoy = date('Y-m-d');
        echo "<script>console.log('hoy es:'+ '. $fecha_hoy.');</script>";
    
        // Consulta para obtener las reservas de hoy ordenadas por ID
        $consulta = "SELECT * FROM Reserva WHERE DATE(Fecha) = '$fecha_hoy' ORDER BY hora_inicio ASC";
        $parametros = array($fecha_hoy);
        
        $this->consultaDatos($consulta, $parametros);
    
        return $this->filas;
    }
    

    // Puedes agregar más funciones según tus necesidades, por ejemplo, para insertar nuevas reservas, actualizar, eliminar, etc.
}
?>
