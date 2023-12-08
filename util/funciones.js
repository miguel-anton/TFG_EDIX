document.addEventListener('DOMContentLoaded', function () {
    var botonesDetalles = document.querySelectorAll('.mostrar-detalles');
    var botonesEnvio = document.querySelectorAll('.envio-detalles');
    
    document.getElementById("expandirHoy").addEventListener("click", expandirPedidosHoy);
    document.getElementById("contraerHoy").addEventListener("click", contraerPedidosHoy);
    document.getElementById("expandir").addEventListener("click", expandirPedidos);
    document.getElementById("contraer").addEventListener("click", contraerPedidos);


    function toggleVisibilidad(elemento) {
        var estilo = window.getComputedStyle(elemento);
        var actualDisplay = estilo.getPropertyValue('display');
    
        elemento.style.display = (actualDisplay === 'none') ? 'table' : 'none';
    }
    
    

    botonesDetalles.forEach(function (boton) {
        boton.addEventListener('click', function () {
            var orderId = boton.getAttribute('data-target');
            toggleVisibilidad(document.getElementById('detalles-' + orderId));
            toggleVisibilidad(document.getElementById('comentarios-' + orderId));
            boton.classList.toggle('active');
        });
    });

    botonesEnvio.forEach(function (boton) {
        boton.addEventListener('click', function () {
            var orderId = boton.getAttribute('data-target');
            toggleVisibilidad(document.getElementById('envio-' + orderId));
            boton.classList.toggle('active');
        });
    });

  
});


    document.addEventListener("DOMContentLoaded", function () {
        // Obtén todos los elementos td con id "tipoEnvio"
        var tipoEnvios = document.querySelectorAll("td#tipoEnvio");

        // Itera sobre cada elemento
        tipoEnvios.forEach(function (tipoEnvio) {
            // Verifica si el valor es "Recogida local"
            var contenido = tipoEnvio.textContent || tipoEnvio.innerText;

            console.log("Tipo de Envío:", contenido.trim());

            if (contenido.trim() === "Recogida local") {
                // Encuentra la fila actual
                var filaTipoEnvio = tipoEnvio.closest("tr");

                // Busca los botones con clase "envio-detalles" dentro de la misma tabla
                var envioDetallesBtns = filaTipoEnvio.parentElement.querySelectorAll(".envio-detalles");

                console.log("Número de botones encontrados:", envioDetallesBtns.length);

                // Oculta todos los botones si existen
                envioDetallesBtns.forEach(function (envioDetallesBtn) {
                    if (envioDetallesBtn) {
                        envioDetallesBtn.style.display = "none";
                        console.log("Botón oculto");
                    } else {
                        console.log("Botón no encontrado");
                    }
                });
            } else {
                console.log("Tipo de envío no es 'Recogida local'");
            }
        });
    });



   

    
    
    function expandirPedidos() {

        var contenedor = document.getElementById('Woocommerce');

        var detallesElements = contenedor.querySelectorAll('.subtabla');
        detallesElements.forEach(function (element) {
            element.style.setProperty("display", "table", "important");
        });
    
        var comentariosElements = contenedor.querySelectorAll('.comentarios');
        comentariosElements.forEach(function (element) {
            element.style.setProperty("display", "table", "important");
        });
    
        var envioElements = contenedor.querySelectorAll('.envios');
        envioElements.forEach(function (element) {
            element.style.setProperty("display", "table", "important");
        });
    }
    
    function contraerPedidos() {

        var contenedor = document.getElementById('Woocommerce');


        var detallesElements = contenedor.querySelectorAll('.subtabla');
        detallesElements.forEach(function (element) {
            element.style.setProperty("display", "none", "important");
        });
    
        var comentariosElements = contenedor.querySelectorAll('.comentarios');
        comentariosElements.forEach(function (element) {
            element.style.setProperty("display", "none", "important");
        });
    
        var envioElements = contenedor.querySelectorAll('.envios');
        envioElements.forEach(function (element) {
            element.style.setProperty("display", "none", "important");
        });
    }
    
    function expandirPedidosHoy() {
        var detallesElements = document.querySelectorAll('[name="subtabla"]');
        detallesElements.forEach(function (element) {
            element.style.setProperty("display", "table", "important");
        });
    
        var comentariosElements = document.querySelectorAll('[name="comentarios"]');
        comentariosElements.forEach(function (element) {
            element.style.setProperty("display", "table", "important");
        });
    
        var envioElements = document.querySelectorAll('[name="envios"]');
        envioElements.forEach(function (element) {
            element.style.setProperty("display", "table", "important");
        });
    }
    
    function contraerPedidosHoy() {
        var detallesElements = document.querySelectorAll('[name="subtabla"]');
        detallesElements.forEach(function (element) {
            element.style.setProperty("display", "none", "important");
        });
    
        var comentariosElements = document.querySelectorAll('[name="comentarios"]');
        comentariosElements.forEach(function (element) {
            element.style.setProperty("display", "none", "important");
        });
    
        var envioElements = document.querySelectorAll('[name="envios"]');
        envioElements.forEach(function (element) {
            element.style.setProperty("display", "none", "important");
        });
    }
    
// mandar a cocina los pedidos

    document.addEventListener('DOMContentLoaded', function () {
        // Selecciona todos los botones "Mandar a Cocina"
        var mandarACocinaButtons = document.querySelectorAll('.mandar-a-cocina');

        // Agrega un evento de clic a cada botón
        mandarACocinaButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                // Encuentra el elemento padre 'pedido'
                var pedidoElement = button.closest('#order');
                
                // Agrega la clase 'eliminado' para activar la transición
                pedidoElement.classList.add('eliminado');

                // Elimina el elemento después de la duración de la transición
                setTimeout(function () {
                    pedidoElement.remove();
                }, 500); // 500 milisegundos, ajusta según la duración de tu transición
            });
        });
    });


    /* FUTURA INCORPORACION CALENDARIO

//calendario de reservas


$(document).ready(function () {
    // Inicializar el selector de fecha (datepicker)
    $("#fechaSelector").datepicker({
        onSelect: function (dateText, inst) {
            // Manejar el cambio de fecha aquí
            // Puedes realizar una petición AJAX para obtener las reservas de la fecha seleccionada
            // y luego actualizar la tabla
            actualizarReservas(dateText);
        }
    });
});

function actualizarReservas(fechaSeleccionada) {
   

    // Ejemplo usando jQuery.ajax
    $.ajax({
        url: 'DaoReservas.php',
        type: 'GET',
        data: { fecha: fechaSeleccionada },
        success: function (data) {
            // Actualizar la tabla con las nuevas reservas
            $("#tablaReservas").html(data);
        },
        error: function () {
            console.log("Error al obtener las reservas.");
        }
    });
}
*/