<?php
/*
*
* ESTE SCRIPT REALMENTE SE ENCUENTRA EN EL ARCHIVO FUNCTIONS.PHP DEL TEMA QUE USO EN EL WORDPRESS
*
*/




// Asegúrate de que esta función se encuentra dentro del archivo functions.php de tu tema
function consultar_disponibilidad_primer_paso() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recoger los datos del formulario
        $num_comensales = intval($_POST['num_comensales']); // Asegúrate de que es un número entero
        $fecha = sanitize_text_field($_POST['fecha']);
        $franja_horaria = sanitize_text_field($_POST['franja_horaria']);

		// Almacena los datos en una sesión para acceder a ellos más tarde
      	session_start();
        $_SESSION['num_comensales'] = $num_comensales;
        $_SESSION['fecha'] = $fecha;
        $_SESSION['franja_horaria'] = $franja_horaria; 


        // Realizar la consulta SQL para verificar la disponibilidad
        $disponibilidad = consultar_disponibilidad($num_comensales, $fecha, $franja_horaria);

        // Mostrar resultados o realizar acciones según la disponibilidad
        if ($disponibilidad) {
            // Hay mesas disponibles, redirigir a otra página o realizar acciones adicionales
            wp_redirect(home_url('/reservas-2')); // redirige a la siguiente parte del formulario
            exit();
        } 
    }
}	
// Añadir la acción para la función en functions.php
add_action('wp', 'consultar_disponibilidad_primer_paso');



// Función para consultar la disponibilidad en la base de datos

function consultar_disponibilidad($num_comensales, $fecha, $franja_horaria) {
	
    global $wpdb;
   

	
    // Inicializar el array de resultados
    $resultados = [];

    // Ajuste en la consulta SQL para considerar la franja horaria, el horario y la nueva relación muchos a muchos
    $sql = $wpdb->prepare(
        "SELECT
            CONCAT(H.Hora_inicio, ':00') AS Hora,
            COUNT(M.ID) AS Mesas_No_Reservadas,
            GROUP_CONCAT(M.ID ORDER BY M.ID ASC) AS ids_disponibles,
            IF(COUNT(M.ID) * 4 >= %d, 'si', 'no') AS Es_Posible_Reservar
        FROM (
            SELECT
                Hora_inicio, Hora_fin
            FROM (
                SELECT '13:00' AS Hora_inicio, '14:00' AS Hora_fin UNION ALL
                SELECT '13:30', '14:30' UNION ALL
                SELECT '14:00', '15:00' UNION ALL
                SELECT '14:30', '15:30' UNION ALL
                SELECT '15:00', '16:00' UNION ALL
                SELECT '15:30', '16:30' UNION ALL
                SELECT '16:00', '17:00'
            ) AS Comida
            WHERE %s = 'comida'
            
            UNION ALL
            
            SELECT
                Hora_inicio, Hora_fin
            FROM (
                SELECT '20:00' AS Hora_inicio, '21:00' AS Hora_fin UNION ALL
				SELECT '20:30', '21:30' UNION ALL
				SELECT '21:00', '22:00' UNION ALL
				SELECT '21:30', '22:30' UNION ALL
				SELECT '22:00', '23:00' UNION ALL
				SELECT '22:30', '23:30' UNION ALL
				SELECT '23:00', '00:00'
            ) AS Cena
            WHERE %s = 'cena'
        ) AS H
        INNER JOIN Mesa M ON 1=1
        LEFT JOIN (
            SELECT
                RM.Mesa_id,
                R.Franja_horaria,
                R.Hora_inicio,
                R.Hora_fin
            FROM Reserva R
            INNER JOIN Reserva_Mesa RM ON R.ID = RM.Reserva_id
            WHERE R.Fecha = %s
                AND R.Franja_horaria = %s
        ) AS Reservas ON M.ID = Reservas.Mesa_id
            AND H.Hora_inicio >= Reservas.Hora_inicio
            AND H.Hora_inicio <= Reservas.Hora_fin
        WHERE Reservas.Mesa_id IS NULL
        GROUP BY H.Hora_inicio
        ORDER BY H.Hora_inicio",
        $num_comensales,
        $franja_horaria,
        $franja_horaria,
        $fecha,
        $franja_horaria
    );

    // Ejecuta la consulta y obtén el resultado
    $resultados = $wpdb->get_results($sql, ARRAY_A);

    

    // Devuelve el array con los resultados
    return $resultados;
	
}



// Función para obtener las horas disponibles
function obtener_horas_disponibles() {
	global $wpdb;

    // Obtener datos del formulario anterior
    $num_comensales = isset($_SESSION['num_comensales']) ? $_SESSION['num_comensales'] : '';
    $fecha = isset($_SESSION['fecha']) ? $_SESSION['fecha'] : '';
    $franja_horaria = isset($_SESSION['franja_horaria']) ? $_SESSION['franja_horaria'] : '';

    // Verificar si se proporcionaron todos los datos necesarios
    if (!$num_comensales || !$fecha || !$franja_horaria) {
        return [];
    }

    // Consultar disponibilidad con los datos del formulario anterior
    $sql = $wpdb->prepare(
        "SELECT
            CONCAT(H.Hora_inicio, ':00') AS Hora,
            COUNT(M.ID) AS Mesas_No_Reservadas,
            GROUP_CONCAT(M.ID ORDER BY M.ID ASC) AS ids_disponibles,
            IF(COUNT(M.ID) * 4 >= %d, 'si', 'no') AS Es_Posible_Reservar
        FROM (
            SELECT
                Hora_inicio, Hora_fin
            FROM (
                SELECT '13:00' AS Hora_inicio, '14:00' AS Hora_fin UNION ALL
                SELECT '13:30', '14:30' UNION ALL
                SELECT '14:00', '15:00' UNION ALL
                SELECT '14:30', '15:30' UNION ALL
                SELECT '15:00', '16:00' UNION ALL
                SELECT '15:30', '16:30' UNION ALL
                SELECT '16:00', '17:00'
            ) AS Comida
            WHERE %s = 'comida'
            
            UNION ALL
            
            SELECT
                Hora_inicio, Hora_fin
            FROM (
                SELECT '20:00' AS Hora_inicio, '21:00' AS Hora_fin UNION ALL
				SELECT '20:30', '21:30' UNION ALL
				SELECT '21:00', '22:00' UNION ALL
				SELECT '21:30', '22:30' UNION ALL
				SELECT '22:00', '23:00' UNION ALL
				SELECT '22:30', '23:30' UNION ALL
				SELECT '23:00', '00:00'

            ) AS Cena
            WHERE %s = 'cena'
        ) AS H
        INNER JOIN Mesa M ON 1=1
        LEFT JOIN (
            SELECT
                RM.Mesa_id,
                R.Franja_horaria,
                R.Hora_inicio,
                R.Hora_fin
            FROM Reserva R
            INNER JOIN Reserva_Mesa RM ON R.ID = RM.Reserva_id
            WHERE R.Fecha = %s
                AND R.Franja_horaria = %s
        ) AS Reservas ON M.ID = Reservas.Mesa_id
            AND H.Hora_inicio >= Reservas.Hora_inicio
            AND H.Hora_inicio <= Reservas.Hora_fin
        WHERE Reservas.Mesa_id IS NULL
        GROUP BY H.Hora_inicio
        ORDER BY H.Hora_inicio",
        $num_comensales,
        $franja_horaria,
        $franja_horaria,
        $fecha,
        $franja_horaria
    );

    // Ejecutar la consulta y obtener el resultado
    $resultados = $wpdb->get_results($sql, ARRAY_A);

    // Filtrar solo las horas en las que es posible reservar
    $horas_disponibles = array_filter($resultados, function ($hora) {
        return $hora['Es_Posible_Reservar'] === 'si';
    });

    return $horas_disponibles;
}

function formulario_reserva_shortcode() {
    session_start();
    $horas_disponibles = obtener_horas_disponibles();
	 

    // Verificar si el formulario ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $num_comensales = intval($_POST['num_comensales']); 
        $fecha = sanitize_text_field($_POST['fecha']);
		$franja_horaria = sanitize_text_field($_POST['horario']);

        $hora_inicio = $_POST['hora_reserva'];
        $hora_fin = date('H:i:s', strtotime($hora_inicio . ' + 1 hour'));

        global $wpdb;
        $tabla_reservas =  'Reserva';

        // Preparar la consulta SQL
        $consulta = $wpdb->prepare("INSERT INTO $tabla_reservas (Num_comensales, Fecha, Franja_horaria, hora_inicio, hora_fin, nombre_cliente, telefono_cliente, correo_cliente) VALUES (%d, %s, %s, %s, %s, %s, %s, %s)", $num_comensales, $fecha, $franja_horaria, $hora_inicio, $hora_fin, $nombre, $telefono, $email);

        // Ejecutar la consulta
        if ($wpdb->query($consulta)) {
			// Obtener el ID de la última reserva insertada
            $id_reserva = $wpdb->insert_id;

            // Asignar mesas a la reserva
            asignar_mesas_a_reserva($id_reserva, $num_comensales, $fecha, $franja_horaria, $hora_inicio);

			die;
        } else {
            echo 'Error al guardar la reserva: ' . $wpdb->last_error;
        }
    }

    // Verificar si hay horas disponibles antes de mostrar el formulario
    if (!empty($horas_disponibles)) {
        echo '<form action="#" method="post" id="formulario-reserva">';
    
       // Mostrar información anterior del formulario
	 	$num_comensales = isset($_SESSION['num_comensales']) ? $_SESSION['num_comensales'] : '';
	 	$fecha = isset($_SESSION['fecha']) ? $_SESSION['fecha'] : '';
	 	$franja_horaria = isset($_SESSION['franja_horaria']) ? $_SESSION['franja_horaria'] : '';
	 
		 echo "<script>console.log('Console: entrando dentro :)'+ '. $franja_horaria.');</script>";
		$datoscomensales =$num_comensales;
		$datosfecha =$fecha;
		$datosfranja = $franja_horaria;
		
		// Campos ocultos para datos previos
		echo '<input type="text" name="num_comensales" style="display:none;" value="' . $datoscomensales . '">';
		echo '<input type="text" name="fecha" style="display:none;" value="' . $datosfecha . '">';
		echo '<input type="text" name="horario" style="display:none;" value="' . $datosfranja . '">';
		echo "<script>console.log('Console: entrando dentro :)'+ '. $datosfranja.');</script>";

		echo '<div id=datos-prev>';
        if ($num_comensales) {
            echo "<p>Número de comensales: $num_comensales</p>";
        }
        if ($fecha) {
            echo "<p>Fecha: $fecha</p>";
        }
        if ($franja_horaria) {
            echo "<p>Franja horaria: $franja_horaria</p>";
        }
		echo '</div>';


        echo '<label for="hora_reserva">Seleccione la hora:</label>';
        // Muestra los botones de las horas disponibles
        echo '<div id="horas-disponibles">';
        echo '<select id="hora_reserva" name="hora_reserva" required>';
        foreach ($horas_disponibles as $hora) {
            echo '<option value="' . esc_attr($hora['Hora']) . '">' . esc_html($hora['Hora']) . '</option>';
        }
        echo '</select>';
        echo '</div>';
        
        // Campos para nombre, teléfono y correo electrónico
        echo '<label for="nombre">Nombre completo :</label>';
        echo '<input type="text" id="nombre" name="nombre" required>';
        
        echo '<label for="telefono">Teléfono:</label>';
        echo '<input type="tel" id="telefono" name="telefono" required>';
        
        echo '<label for="email">Correo Electrónico:</label>';
        echo '<input type="email" id="email" name="email" required>';
        echo '<div style="display:flex; justify-content:center">';
        echo '<button id="confirmar-reserva-segunda-parte-btn" type="submit">Confirmar Reserva</button>';
        echo '</div>';
		echo '</form>';
    } else {
        echo 'Lo sentimos, no hay mesas disponibles para esa capacidad en la franja horaria seleccionada, prueba para otro dia.';
    }
}
add_shortcode('obtener_horas_disponibles', 'formulario_reserva_shortcode');

function asignar_mesas_a_reserva($id_reserva, $num_comensales, $fecha, $franja_horaria, $hora_inicio) {
    global $wpdb;

    // Consultar disponibilidad con los datos de la reserva
    $mesas_disponibles = consultar_disponibilidad($num_comensales, $fecha, $franja_horaria);

    

    // Filtrar las mesas disponibles solo para la franja horaria de la reserva
    $mesas_disponibles_franja = array_filter($mesas_disponibles, function ($mesa) use ($hora_inicio) {
        return $mesa['Hora'] === $hora_inicio;
    });

    // Obtener solo los IDs de las mesas disponibles en la franja horaria
    $mesas_disponibles_ids = [];
    foreach ($mesas_disponibles_franja as $mesa) {
        $ids_disponibles = explode(',', $mesa['ids_disponibles']);
        $mesas_disponibles_ids = array_merge($mesas_disponibles_ids, $ids_disponibles);
    }

    // Tomar las primeras mesas disponibles de la franja horaria de la reserva
    $mesas_asignadas_ids = array_slice($mesas_disponibles_ids, 0, $num_comensales);

	$num_mesas_necesarias = ceil($num_comensales / 4);


    

	// Depuración: mostrar información sobre las mesas asignadas
	/*
	echo 'Mesas disponibles: ' . print_r($mesas_disponibles, true) . '<br>';
	echo 'Mesas disponibles fraja: ' . print_r($mesas_disponibles_franja, true) . '<br>';
	echo 'Mesas disponibles: ' . print_r($num_mesas_necesarias, true) . '<br>';

	echo 'Mesas disponibles ids: ' . print_r($mesas_disponibles_ids, true) . '<br>';
	echo 'Mesas asignadas IDs: ' . print_r($mesas_asignadas_ids, true) . '<br>';
	*/

    // Verificar si hay suficientes mesas disponibles
    if (count($mesas_asignadas_ids) < $num_mesas_necesarias) {
        // No hay suficientes mesas, mostrar mensaje y detener el proceso
        echo 'No hay suficientes mesas disponibles para satisfacer la reserva.';
        return;
    }

	
	// Insertar las asignaciones en la tabla Reserva_Mesa
    for ($i = 0; $i < $num_mesas_necesarias; $i++) {
        // Insertar en la tabla Reserva_Mesa
        $result = $wpdb->insert(
            'Reserva_Mesa',
            array(
                'Reserva_ID' => $id_reserva,
                'Mesa_ID' => $mesas_asignadas_ids[$i]
            ),
            array('%d', '%d')
        );
		/*
        if (false === $result) {
            // Hubo un error al insertar la mesa, manejar según tu lógica (lanzar error, mensaje, etc.)
            echo 'Hubo un error al asignar la mesa ' . $mesas_asignadas_ids[$i] . ' a la reserva.';
            return;
        }*/
    }


    // Si llegamos aquí, las mesas se han asignado correctamente
    echo 'Has reservado con éxito.';
	die;
}

?>
