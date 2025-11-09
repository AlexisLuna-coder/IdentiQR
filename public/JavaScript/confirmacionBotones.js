/*
document.getElementById('BajaUsuario_EliminarUsuario').addEventListener('click', function(e) {
    e.preventDefault(); // evitamos submit inmediato
    const form = document.getElementById('formBajaUsuario');

    Swal.fire({
        title: "¿Estás seguro de eliminar este usuario?",
        text: "¡No podrás revertir esta acción!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar el formulario al servidor
            form.submit();
            // Opcional: mostrar una alerta inmediata (la respuesta del servidor puede mostrar otra)
            // Swal.fire("Eliminando...", "", "info");
        }
    });
});
*/

// confirmacionBotones.js
/*
document.addEventListener('DOMContentLoaded', function () {
    const btnEliminar = document.getElementById('BajaUsuario_EliminarUsuario');
    const form = document.getElementById('formBajaUsuario');
    const inputCred = document.getElementById('idUsuario_BajaUSUARIO');

    btnEliminar.addEventListener('click', function(e) {
        e.preventDefault();

        // VALIDACIÓN: si el campo está vacío, mostrar error y no abrir confirm
        const valor = (inputCred.value || '').trim();
        if (!valor) {
            Swal.fire({
                title: "Campo vacío",
                text: "Por favor escribe el usuario, correo o id a eliminar.",
                icon: "warning"
            });
            return;
        }

        // Mostrar confirmación
        Swal.fire({
            title: "¿Estás seguro de eliminar este usuario?",
            text: "¡No podrás revertir esta acción!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar"
        }).then((result) => {
            if (result.isConfirmed) {
                // enviar formulario (comportamiento normal)
                form.submit();
            }
        });
    });
});
*/

//Función para crear el Dialog o alert de confirmación de borrado
function confirmacionEliminacionUsr(event){
    event.preventDefault();  // Prevenir envío inmediato del form
    const inputCred = document.getElementById('idUsuario_BajaUSUARIO');
    const valor = (inputCred.value || '').trim();

    if (!valor) {
        Swal.fire({
            title: "Campo vacío",
            text: "Por favor escribe el usuario, correo o id a eliminar.",
            icon: "warning"
        });
        return false;  // No continuar con el submit ni confirmación
    }
    Swal.fire({
        title: "¿Desea continua con la eliminación?",
        text: "¡No podrás revertir esta acción!",
        theme: 'material-ui',
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar."
    }).then((result) => {
        if (result.isConfirmed) {
            // Si confirma, enviamos el formulario manualmente
            document.getElementById('formBajaUsuario').submit();
        }
    });

    // Retornamos false para prevenir el submit normal
    return false;
}
//Función para mostrar el usuario/alumno que fue borrado del sistema.
function mostrarAlerta(tipo, mensaje) {
    Swal.fire({
        icon: tipo,
        title: tipo === 'success' ? '¡Éxito!' : 'Error',
        text: mensaje,
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false
    });
}

//Función para mostrar que se agrego un nuevo Usuario/Alumno al sistema. Boton de confirmación e información.
function mostrarRegistro(){
    Swal.fire({
        title: "Registro exitoso. IdentiQR!",
        text: "El registro del Usuario/Alumno se realizo de manera correcta.",
        imageUrl: "/IdentiQR/public/Media/img/Logo.png",
        imageWidth: 200,
        imageHeight: 150,
        imageAlt: "Logo IdentiQR"
    });
}

//Consultar registros 
function seleccionarAccion(event, accionValue) {
    event.preventDefault(); // Prevenir el submit automático
    const inputAccion = document.getElementById('accion');
    inputAccion.value = accionValue;

    const inputUsuario = document.getElementById('idUsuario_ConsultarUSUARIO').value.trim();

    if (accionValue === 'buscar' && inputUsuario === '') {
        Swal.fire({
            title: "Campo vacío",
            text: "Por favor ingresa el usuario o correo a consultar.",
            icon: "warning",
            timer: 2000,
            showConfirmButton: false
        });
        return; // No enviar formulario si está vacío
    }

    Swal.fire({
        title: accionValue === 'buscar' ? "Consultando usuario..." : "Consultando todos los usuarios...",
        icon: "info",
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        document.getElementById('formConsultaUsuario').submit();
    });
}


function consultarTodo(event) {
    event.preventDefault(); // también evita redirección o envío

    Swal.fire({
        title: "Consultando todos los usuarios...",
        text: "Por favor espera un momento.",
        icon: "info",
        timer: 2000,
        showConfirmButton: false
    });
    // Espera los 2 segundos del SweetAlert antes de redirigir
    setTimeout(() => {
        window.location.href = "/IdentiQR/app/Controllers/ControladorUsuario.php?action=consultarUsuario&consultarTodo=1";
    }, 2000);
}

// confirmacionBotones.js (parte pública)
function mostrarAlertaBusqueda(tipo, mensaje) {
    // tipo: 'info' | 'success' | 'error'
    if (typeof Swal === 'undefined') {
        console.warn('SweetAlert2 no está cargado.');
        alert(mensaje); // fallback
        return;
    }

    Swal.fire({
        icon: tipo || 'info',
        title: tipo === 'success' ? 'Éxito' : (tipo === 'error' ? 'Error' : 'Información'),
        text: mensaje || '',
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false
    });
}

/*4. MENSAJES DE NOTIIFCACIONES DE ELIMINACIÓN */
function confirmacionEliminacionUsuario(event){
    event.preventDefault();  // Prevenir envío inmediato del form
    const inputCred = document.getElementById('idUsuario_BajaUSUARIO');
    const valor = (inputCred.value || '').trim();

    if (!valor) {
        Swal.fire({
            title: "Campo vacío",
            text: "Por favor ingresa la matricula del usuario a eliminar.",
            icon: "warning"
        });
        return false;  // No continuar con el submit ni confirmación
    }
    Swal.fire({
        title: "¿Desea continua con la eliminación?",
        text: "¡No podrás revertir esta acción!",
        theme: 'material-ui',
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar."
    }).then((result) => {
        if (result.isConfirmed) {
            // Si confirma, enviamos el formulario manualmente
            document.getElementById('formBajaUsuario').submit();
        }
    });

    // Retornamos false para prevenir el submit normal
    return false;
}

/*4.1. CONFIRMACIÓN DE ELIMINACIÓN DESDE LA TABLA DE USUARIOS */
function confirmacionEliminacionUsuarioTabla(event, usuario) {
    event.preventDefault();  // Prevenir cualquier acción por defecto
    
    if (!usuario || usuario.trim() === '') {
        Swal.fire({
            title: "Error",
            text: "No se pudo obtener el usuario.",
            icon: "error"
        });
        return false;
    }
    
    Swal.fire({
        title: "¿Desea eliminar este usuario?",
        text: `Usuario: ${usuario}\n¡No podrás revertir esta acción!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir al controlador con GET
            window.location.href = `/IdentiQR/app/Controllers/ControladorUsuario.php?action=eliminarUsuario&usuario=${encodeURIComponent(usuario)}`;
        }
    });
    
    return false;
}
