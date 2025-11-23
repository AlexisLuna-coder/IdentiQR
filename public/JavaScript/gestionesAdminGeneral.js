/*AQUÍ IRA LOS DEL BACKUP Y RESTORE. ASÍ COMO PARA "GENERANDO REPORTES" */
/*GESTIONES ADMIN GENERAL */
/**
 * Archivo JS para las gestiones generales del Administrador (Backup, Restore, Reportes)
 */

document.addEventListener("DOMContentLoaded", function() {
    // Verificar si hay alertas pendientes del servidor (Restore success/error)
    manejarAlertasBD();
});

/**
 * Muestra alerta de carga para el BACKUP y luego redirige a la descarga
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
 * Muestra alerta de carga infinita para el RESTORE hasta que el servidor responda
 */
function confirmarRestore(event) {
    event.preventDefault(); // Detenemos el envío inmediato del formulario
    
    // Validar que haya archivo seleccionado
    const input = document.getElementById('backupFile');
    if (!input || !input.files.length) {
        Swal.fire('Atención', 'Por favor selecciona un archivo .sql primero', 'warning');
        return;
    }

    // Mostrar alerta de confirmación (Pregunta de seguridad)
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
            // Si el usuario confirma, mostramos la alerta de carga
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

            // 4. Enviamos el formulario manualmente
            event.target.submit();
        }
    });
}

/**
 * Lee el input hidden que imprime PHP para mostrar Éxito o Error tras el Restore
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
        // Extraemos el mensaje de error si viene separado por "::"
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

// Función PARA LOS REPORTES
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