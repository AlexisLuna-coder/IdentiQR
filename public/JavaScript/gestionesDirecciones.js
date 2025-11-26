/* Este archivo funcionara para el uso de JS de las Direcciones (GestionesAdmin_Direccion.php, gestionJustificantes_Dir.php, etc.) */

// Variables globales para el escáner
let scanner = null;

// Función para abrir el modal de escaneo
function abrirModal() {
    document.getElementById('modalEscanear').style.display = 'block';
    iniciarScanner();
}

// Función para cerrar el modal de escaneo
function cerrarModal() {
    document.getElementById('modalEscanear').style.display = 'none';
    detenerScanner();
}

// Variable global para saber si el escaneo es para consulta
let escaneoParaConsulta = false;

// Función para iniciar el escáner
function iniciarScanner() {
    const video = document.getElementById('video');
    const estado = document.getElementById('estado');
    const datosQR = document.getElementById('datosQR');

    if (!video || !estado || !datosQR) {
        console.error('Elementos del modal no encontrados');
        return;
    }

    // Limpiar datos anteriores
    datosQR.innerHTML = '<p class="text-muted">Acerque el Código QR a escanear.</p>';
    estado.innerHTML = 'Iniciando escáner...';

    if (scanner) {
        scanner.stop();
    }

    scanner = new Instascan.Scanner({ video: video });

    const canvas = document.createElement('canvas');
    canvas.getContext('2d', { willReadFrequently: true });

    scanner.addListener('scan', function (content) {
        // Mostrar el contenido escaneado
        datosQR.innerHTML = '<pre>' + escapeHtml(content) + '</pre>';

        // Procesar el contenido del QR
        procesarQR(content, escaneoParaConsulta);

        // Cerrar modal automáticamente después de 1 segundo
        setTimeout(function() {
            cerrarModal();
            escaneoParaConsulta = false; // Resetear la variable
        }, 1000);
    });

    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            let selectedCamera = cameras[0];
            if (isMobile) {
                // Preferir cámara trasera en dispositivos móviles
                for (let cam of cameras) {
                    if (cam.name.toLowerCase().includes('back') || cam.name.toLowerCase().includes('rear') || cam.name.toLowerCase().includes('trasera')) {
                        selectedCamera = cam;
                        break;
                    }
                }
                // Si no se encontró por nombre, asumir que la última es la trasera
                if (selectedCamera === cameras[0] && cameras.length > 1) {
                    selectedCamera = cameras[cameras.length - 1];
                }
            }
            scanner.start(selectedCamera);
            estado.innerHTML = 'Cámara activada. Escaneando...';
        } else {
            estado.innerHTML = '<span class="text-danger">No se encontraron cámaras.</span>';
        }
    }).catch(function (e) {
        estado.innerHTML = '<span class="text-danger">Error al acceder a la cámara: ' + e.message + '</span>';
    });
}

// Función para detener el escáner
function detenerScanner() {
    if (scanner) {
        scanner.stop();
        scanner = null;
    }
}

// Función para procesar el contenido del QR
function procesarQR(content, esConsulta = false) {
    try {
        // Asumir que el QR contiene datos en formato JSON o texto plano con líneas
        let data = content;

        // Intentar parsear como JSON
        try {
            data = JSON.parse(content);
        } catch (e) {
            // Si no es JSON, procesar como texto plano
            const lines = content.split('\n');
            data = {};
            lines.forEach(line => {
                const parts = line.split(':');
                if (parts.length >= 2) {
                    const key = parts[0].trim();
                    const value = parts.slice(1).join(':').trim();
                    data[key] = value;
                }
            });
        }

        // Extraer la matrícula
        let matricula = null;
        if (typeof data === 'object') {
            matricula = data.Matricula || data.matricula || data.matriculaEscaneado || data.id || null;
        } else if (typeof data === 'string') {
            // Buscar patrón de matrícula en el string
            const matriculaMatch = data.match(/\b\d{8,10}\b/);
            if (matriculaMatch) {
                matricula = matriculaMatch[0];
            }
        }

        if (matricula) {
            if (esConsulta) {
                // Rellenar los campos de consulta
                const inputMatriculaVisible = document.getElementById('matriculaConsultaVisible');
                const inputMatriculaHidden = document.getElementById('matriculaConsulta');

                if (inputMatriculaVisible) {
                    inputMatriculaVisible.value = matricula;
                }
                if (inputMatriculaHidden) {
                    inputMatriculaHidden.value = matricula;
                }
            } else {
                // Rellenar los campos de registro
                const inputMatricula = document.getElementById('matriculaEscaneado');
                const inputMatriculaBD = document.getElementById('matriculaEscaneadoBD');

                if (inputMatricula) {
                    inputMatricula.value = matricula;
                }
                if (inputMatriculaBD) {
                    inputMatriculaBD.value = matricula;
                }
            }

            // Mostrar mensaje de éxito
            Swal.fire({
                title: "QR Escaneado",
                text: "Matrícula detectada: " + matricula,
                icon: "success",
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            // Mostrar mensaje de error
            Swal.fire({
                title: "Error",
                text: "No se pudo detectar la matrícula en el QR.",
                icon: "error"
            });
        }
    } catch (error) {
        console.error('Error procesando QR:', error);
        Swal.fire({
            title: "Error",
            text: "Error al procesar el QR.",
            icon: "error"
        });
    }
}

// Función para escapar texto HTML
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '<',
        '>': '>',
        '"': '"',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Event listeners
window.addEventListener("load", function() {
    manejarAlertasServidor(); // <--- Funciones de notificaciones
    cargarTutoriasIndividuales(); //Así hacemos que se pueda usar el check
    setFechaSolicitudHoy(); //Función para la asignación de fechas
    reporteIndividualizado_DirMed(); // Función para el reporte individualizado
    // Event listener para el botón de escaneo (registro)
    const btnEscanear = document.getElementById('btnEscanear');
    if (btnEscanear) {
        btnEscanear.addEventListener('click', function(e) {
            e.preventDefault();
            escaneoParaConsulta = false;
            abrirModal();
        });
    }

    // Event listener para el botón de escaneo (consulta)
    const btnEscanearConsulta = document.getElementById('btnEscanearConsulta');
    if (btnEscanearConsulta) {
        btnEscanearConsulta.addEventListener('click', function(e) {
            e.preventDefault();
            escaneoParaConsulta = true;
            abrirModal();
        });
    }

    // Event listener para cerrar modal al hacer clic fuera
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('modalEscanear');
        if (event.target === modal) {
            cerrarModal();
        }
    });

    // Limpiar cámara si el usuario cierra/recarga la página
    window.addEventListener('beforeunload', function() {
        detenerScanner();
    });
});

function mostrarCampo(tipo) {
    // Oculta todos los campos
    document.getElementById("campoMatricula").style.display = "none";
    document.getElementById("campoFolio").style.display = "none";
    document.getElementById("campoTramite").style.display = "none";

    // Muestra el campo correspondiente
    if (tipo === "matricula") {
        document.getElementById("campoMatricula").style.display = "block";
    } else if (tipo === "folio") {
        document.getElementById("campoFolio").style.display = "block";
    } else if (tipo === "tramite") {
        document.getElementById("campoTramite").style.display = "block";
    }
}


// Función para mostrar campos de consulta
function mostrarCampoConsulta(tipo) {
    // Oculta todos los campos de consulta
    document.getElementById("campoTodosConsulta").style.display = "none";
    document.getElementById("campoMatriculaConsulta").style.display = "none";
    document.getElementById("campoFolioConsulta").style.display = "none";
    document.getElementById("campoTramiteConsulta").style.display = "none";

    // Muestra el campo correspondiente
    if (tipo === "todos") {
        document.getElementById("campoTodosConsulta").style.display = "block";
    } else if (tipo === "matricula") {
        document.getElementById("campoMatriculaConsulta").style.display = "block";
    } else if (tipo === "folio") {
        document.getElementById("campoFolioConsulta").style.display = "block";
    } else if (tipo === "tramite") {
        document.getElementById("campoTramiteConsulta").style.display = "block";
    }
}

// Función para confirmar eliminación desde la tabla (por FolioSeguimiento)
function confirmarEliminacion(controller,folioSeguimiento,idDepto) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar el trámite con Folio de Seguimiento: " + folioSeguimiento + "?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear un formulario para enviar el FolioSeguimiento por POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/IdentiQR/redireccionAcciones.php?controller='+controller+'&action=deleteFS&idDepto='+idDepto;
            //form.action = 'GestionesAdmin_Direccion.php?action=deleteFS';
            
            // Agregar campo FolioSeguimiento
            const inputFolio = document.createElement('input');
            inputFolio.type = 'hidden';
            inputFolio.name = 'FolioSeguimiento';
            inputFolio.value = folioSeguimiento;
            form.appendChild(inputFolio);
            
            // Agregar campo idDepto
            const inputDepto = document.createElement('input');
            inputDepto.type = 'hidden';
            inputDepto.name = 'idDepto';
            inputDepto.value = idDepto;
            form.appendChild(inputDepto);
            
            // Agregar campo de acción
            const inputAccion = document.createElement('input');
            inputAccion.type = 'hidden';
            inputAccion.name = 'accionEliminar';
            inputAccion.value = 'eliminarTramite';
            form.appendChild(inputAccion);
            
            // Agregar formulario al body y enviarlo
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Función para confirmar eliminación desde el formulario (por FolioSeguimiento)
function confirmarEliminacionFS(event) {
    event.preventDefault();
    const folioSeguimiento = document.getElementById('FolioSeguimiento').value;
    const form = event.target;
    
    if (!folioSeguimiento) {
        Swal.fire({
            title: 'Error',
            text: 'Por favor ingrese un Folio de Seguimiento',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return false;
    }

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar el trámite con Folio de Seguimiento: " + folioSeguimiento + "?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Agregar campo hidden para confirmar la acción
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'accionEliminar';
            hiddenInput.value = 'eliminarTramite';
            form.appendChild(hiddenInput);
            
            // Enviar el formulario
            form.submit();
        }
    });
    
    return false;
}

/*FUNCIONES EXTRA PARA MEJORA DE FUNCIONAMIENTO */
//1. Aquí debera ir el script que oculta el campo de Tutorias individuales
function cargarTutoriasIndividuales() {
    const check = document.getElementById("tutoriasIndv_Check");
    const campo = document.getElementById("tutoriasIndv_Campo");

    // Si alguno no existe, salimos sin hacer nada (evita el error)
    if (!check || !campo) {
        return;
    }

    // Inicializar el estado según el valor actual del checkbox
    campo.style.display = check.checked ? "block" : "none";
    if (!check._tutoriasListenerAdded) {
        check.addEventListener("change", () => {
            campo.style.display = check.checked ? "block" : "none";
        });
        check._tutoriasListenerAdded = true;
    }
}

//2. Aquí se encuentra el script para la asignación de FECHA EN fechaSolicitud
function setFechaSolicitudHoy() {
    const hoy = new Date();
    const año = hoy.getFullYear();
    const mes = String(hoy.getMonth() + 1).padStart(2, '0');
    const dia = String(hoy.getDate()).padStart(2, '0');
    const fechaActual = `${año}-${mes}-${dia}`;

    // Selecciona por name para cubrir ambos inputs (visible y hidden)
    const inputs = document.querySelectorAll('input[name="fechaSolicitud"]');
    inputs.forEach(input => {
        // Si el input es disabled, seguirá mostrando la fecha en pantalla
        // El hidden con el mismo name también se llenará y será enviado en el formulario.
        input.value = fechaActual;
    });
}
/********************************************** */
// Función para el reporte individualizado de la dirección médica
function reporteIndividualizado_DirMed() {
    // Evitar inicializar dos veces
    if (reporteIndividualizado_DirMed._inited) return;
    reporteIndividualizado_DirMed._inited = true;

    const radios = Array.from(document.querySelectorAll('input[name="tipoReporte"]'));
    const camposFechas = document.getElementById('camposFechas');
    const camposGenero = document.getElementById('camposGenero');
    const fe1 = document.getElementById('fe1');
    const fe2 = document.getElementById('fe2');
    const form = document.getElementById('formRepInd') || document.querySelector('form[action*="repInd_DirMed"]');

    if (!radios.length) return; // Si no hay radios, salimos

    function toggleCampos() {
        const selected = document.querySelector('input[name="tipoReporte"]:checked');
        const value = selected ? selected.value : null;

        // 1. Solamente oculta hasta que el radio esté en su opción (display: '')
        if (camposFechas) camposFechas.style.display = (value === '1') ? 'block' : 'none';
        if (camposGenero) camposGenero.style.display = (value === '2') ? 'block' : 'none';
    }

    // Añadir listener a radios
    radios.forEach(r => {
        if (!r._repListenerAdded) {
            r.addEventListener('change', toggleCampos);
            r._repListenerAdded = true;
        }
    });

    // Validación inmediata de fe2 respecto a fe1 (AL FINAL DE LA LÍNEA)
    if (fe2 && fe1 && !fe2._repListenerAdded) {
        fe2.addEventListener('change', () => {
            if (fe1.value && fe2.value) {
                const d1 = new Date(fe1.value);
                const d2 = new Date(fe2.value);
                if (d2 < d1) {
                    // Manda alert()
                    alert('La Fecha 2 no puede ser previa a la Fecha 1.');
                    fe2.value = '';
                    fe2.focus();
                }
            }
        });
        fe2._repListenerAdded = true;
    }

    // Prevención del submit (Validación mínima requerida)
    if (form && !form._repSubmitAdded) {
        form.addEventListener('submit', (e) => {
            const selected = document.querySelector('input[name="tipoReporte"]:checked');
            
            if (!selected) {
                e.preventDefault();
                alert('Debes elegir el tipo de reporte (Rango de fechas o Género).');
                return;
            }

            if (selected.value === '1') {
                // Modo rango de fechas: solo validar el orden de fe2 respecto a fe1 (ya validado en change)
                if (fe1.value && fe2.value) {
                    const d1 = new Date(fe1.value);
                    const d2 = new Date(fe2.value);
                    if (d2 < d1) {
                        // Aunque el change debería haberlo prevenido, lo bloqueamos aquí por seguridad.
                        e.preventDefault();
                        alert('La Fecha 2 no puede ser previa a la Fecha 1.');
                        return;
                    }
                }
            }
            // Si todo OK o si es modo Género, permite enviar
        });
        form._repSubmitAdded = true;
    }

    // Inicializar estado de campos (IMPORTANTE para cuando carga la página)
    toggleCampos();
}

/**
 * FUNCIONES QUE PERMITIRÁN REALIZAR LA BUSQUEDA-SWEET ALERT
 */
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

    const inputUsuario = document.getElementById('FolioAct').value.trim();

    if (accionValue === 'buscar' && inputUsuario === '') {
        Swal.fire({
            title: "Campo vacío",
            text: "Por favor ingresa el Folio a consult.",
            icon: "warning",
            timer: 2000,
            showConfirmButton: false
        });
        return; // No enviar formulario si está vacío
    }

    Swal.fire({
        title: accionValue === 'buscar' ? "Consultando tramite..." : "Consultando todos los tramites...",
        icon: "info",
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        document.getElementById('formConsultaModificacion').submit();
    });
}
function consultarTodo(event) {
    event.preventDefault(); // también evita redirección o envío

    Swal.fire({
        title: "Consultando todos los tramites...",
        text: "Por favor espera un momento.",
        icon: "info",
        timer: 1500,
        showConfirmButton: false
    });
    // Espera los 2 segundos del SweetAlert antes de redirigir
    setTimeout(() => {
        //window.location.href = "/IdentiQR/app/Controllers/ControladorUsuario.php?action=consultarUsuario&consultarTodo=1";
        //window.location.reload();
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


/**
 * FUNCIÓN que Maneja las alertas enviadas desde PHP mediante el input hidden #serverStatusAlert
 **/
function manejarAlertasServidor() {
    const inputStatus = document.getElementById('serverStatusAlert');
    
    if (!inputStatus || !inputStatus.value) {
        return; // No hay alerta que mostrar
    }

    const status = inputStatus.value;

    // Limpiamos el value para que si recarga por JS no salga de nuevo (aunque en recarga de página sí saldrá)
    // inputStatus.value = ''; 

    if (status === 'error_matricula') {
        Swal.fire({
            title: 'Error',
            text: 'No se encontró el alumno con la matrícula proporcionada',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    } else if (status === 'success') {
        Swal.fire({
            title: 'Éxito',
            text: 'Registro guardado correctamente',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    } else if (status === 'error_bd') {
        Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al guardar en la Base de Datos',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    } else if (status === 'success_update') {
        Swal.fire({
            title: '¡Actualizado!',
            text: 'La información del trámite se ha modificado exitosamente.',
            icon: 'success',
            confirmButtonText: 'Excelente'
        });
    } else if (status === 'error_folio') {//ERROR AL BUSCAR FOLIO PARA ACTUALIZAR
        Swal.fire({ 
            title: 'No encontrado', 
            text: 'El Folio ingresado no existe o no se encuentra registrado.', 
            icon: 'warning', 
            confirmButtonText: 'Entendido' 
        });
    }
}

/**
 * Función que Muestra alerta de carga antes de enviar el formulario
 */
function consultarConCarga(event) {
    event.preventDefault(); // Detener envío inmediato
    const form = event.target; // Obtener el formulario que disparó el evento
    const submitter = event.submitter;  

    Swal.fire({
        title: 'Consultando...',
        text: 'Buscando información, por favor espere.',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        timer: 1500, // Tiempo de espera (estético)
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        // Una vez termina el timer, enviamos el formulario manualmente
        // form.submit() por defecto NO envía el valor del botón submit, por eso PHP fallaba.
        if (submitter && submitter.name) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = submitter.name; // Ej: 'consultarTramite_Depto'
            hiddenInput.value = submitter.value;
            form.appendChild(hiddenInput);
        }
        
        // Enviar formulario manualmente. Ahora PHP sí detectará el $_POST correcto.
        form.submit();
    });
}