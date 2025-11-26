/* 
 * Archivo JS para las gestiones generales del Administrador (Backup, Restore, Reportes)
 * Este archivo contiene funciones que muestran diálogos de confirmación y alertas
 * usando SweetAlert2 para las operaciones de respaldo y restauración de la base de datos,
 * así como para la generación de reportes.
 */

document.addEventListener("DOMContentLoaded", function() {
    // Al cargar la página, verificar si hay alertas generadas por el servidor (ej. éxito o error de restauración)
    manejarAlertasBD();
    //Lógica para manejar el formulario de reportes
    configurarFormularioReportes();
});

/**
 * Muestra alerta de carga para el BACKUP y luego redirige a la descarga.
 * 
 * @param {Event} event - Evento click a cancelar para mostrar alerta primero.
 * @param {string} url - URL para descarga del backup.
 */
function confirmarBackup(event, url) {
    event.preventDefault(); // Evita que el link navegue inmediatamente

    let timerInterval;
    Swal.fire({
        title: 'Generando Respaldo',
        html: 'Preparando archivo SQL...<br>La descarga comenzará en breve.',
        icon: 'info',
        timer: 3000, // Tiempo estimado de espera visual
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    }).then((result) => {
        // Una vez se cierra la alerta (o termina el timer), forzamos la descarga
        window.location.href = url;
    });
}

/**
 * Muestra alerta de confirmación para el RESTORE.
 * Valida que haya archivo seleccionado y pregunta confirmación antes de enviar el formulario.
 * Muestra alerta de carga mientras el servidor procesa la restauración.
 */
function confirmarRestore(event) {
    event.preventDefault(); // Detenemos el envío inmediato del formulario
    // Validar que haya archivo seleccionado para restaurar
    const input = document.getElementById('backupFile');
    if (!input || !input.files.length) {
        Swal.fire('Atención', 'Por favor selecciona un archivo .sql primero', 'warning');
        return;
    }
    // Mostrar alerta de confirmación para que usuario confirme restauración de BD
    Swal.fire({
        title: '¿Confirmar Restauración?',
        text: "Esta acción reemplazará los datos actuales con el archivo seleccionado. Se recomienda haber hecho un respaldo previo.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, mostramos la alerta de carga durante el proceso
            Swal.fire({
                title: 'Restaurando Base de Datos',
                text: 'Este proceso puede tardar unos segundos. Por favor, no cierres la página.',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            // Enviar el formulario manualmente al servidor para iniciar restauración
            event.target.submit();
        }
    });
}

/**
 * Lee el input hidden generado por PHP para determinar si hubo éxito o error en la restauración
 * y muestra la alerta correspondiente con SweetAlert2.
 */
function manejarAlertasBD() {
    const inputStatus = document.getElementById('serverStatusBD');
    
    if (!inputStatus || !inputStatus.value) return;

    const status = inputStatus.value;

    if (status === 'restore_success') {
        Swal.fire({
            title: 'Restauración Exitosa',
            text: 'La base de datos ha sido restaurada correctamente.',
            icon: 'success',
            confirmButtonText: 'Excelente'
        });
    } else if (status.startsWith('restore_error')) {
        // Extraemos el mensaje de error separado por "::"
        const partes = status.split('::');
        const msg = partes[1] || 'Ocurrió un error desconocido.';
        
        Swal.fire({
            title: 'Error en Restauración',
            text: msg,
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
    }
}

// Función para mostrar alerta al generar reportes
function generarReporteAlert() {
    Swal.fire({
        title: 'Generando Reporte',
        text: 'Procesando datos...',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    });
    return true;
}

/*FUNCIÓN QUE PERMITIRA AUTOSELECCIONAR/ASIGNAR EL ACTION A CADA REPORTE*/
function configurarFormularioReportes() {
    //Creamos una constante la cual establecerá diferentes parametros. Servira de arreglo de opciones
    const arregloOpcion = {
        'Tramites': 'tramitesGenerales',
        'Usuarios': 'usuariosGenerales',
        'Alumnos': 'alumnosGenerales'
    };
    const form = document.getElementById('formReportes');
    const select = document.getElementById('tipoReporte');

    // Comprobación de que los elementos existen
    if (!form || !select) {
        console.error('No se encontraron los elementos del formulario de reportes (formReportes o tipoReporte).');
        return;
    }

    // Al cambiar la selección actualizamos la acción del form (buena UX)
    select.addEventListener('change', () => {
        const val = select.value;
        const act = arregloOpcion[val];
        if (act) {
            form.action = `/IdentiQR/redireccionAcciones.php?controller=reportsGeneral&action=${act}`;
        }
    });

    // Antes de enviar, validamos selección y forzamos la acción correcta
    form.addEventListener('submit', (e) => {
        const val = select.value;
        if (!val) {
            e.preventDefault();
            Swal.fire('Atención', 'Por favor selecciona el tipo de reporte que deseas generar.', 'warning');
            select.focus();
            return;
        }
        const act = mapping[val];
        if (act) {
            // Forzar la acción correcta justo antes de enviar
            form.action = `/IdentiQR/redireccionAcciones.php?controller=reportsGeneral&action=${act}`;
        }
    });
}