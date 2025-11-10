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
document.addEventListener("DOMContentLoaded", function() {
    cargarTutoriasIndividuales(); //Así hacemos que se pueda usar el check
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
/********************************************** */

/*
        <script>
            // Evitar regresar con el botón "Atrás"
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function () {
                window.history.pushState(null, "", window.location.href);
                alert("No puedes regresar a la página anterior.");
            };

            // Evitar recargar (F5 o Ctrl+R)
            document.addEventListener("keydown", function (e) {
                if ((e.key === "F5") || 
                    (e.ctrlKey && e.key === "r") || 
                    (e.metaKey && e.key === "r")) {
                    e.preventDefault();
                    alert("La recarga de la página está deshabilitada.");
                }
            });

            // También evita recargar con clic derecho → “Recargar”
            window.onbeforeunload = function () {
                return "¿Estás seguro de que deseas salir de esta página?";
            };
        </script>
        -->
        <script>
            (function() {
            // Añade varios estados para "llenar" el historial localmente
            function pushStates(n = 2) {
                try {
                for (let i = 0; i < n; i++) {
                    history.pushState({preventBack: true, i}, "", window.location.href);
                }
                } catch (err) { }
            }

            // En carga, agrega estados
            window.addEventListener("load", function() {
                pushStates(2);
            });

            // Maneja cuando el usuario intenta retroceder
            window.addEventListener("popstate", function (e) {
                // Si viene de nuestros estados, lo 'empujamos' de regreso
                if (e.state && e.state.preventBack) {
                // Opcional: mostrar mensaje breve al usuario
                // (evita alert() intrusivos si no quieres interrumpir)
                // alert("No puedes regresar a la página anterior.");

                // Reponer estado inmediatamente para impedir retroceso
                try { history.pushState(null, "", window.location.href); } catch (err) {}

                // Intento extra por si el navegador lo permite
                try { history.forward(); } catch (err) {}

                // Alternativa: redirigir a una página segura (descomenta si prefieres esto)
                // window.location.replace("/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php");
                } else {
                // Si no es nuestro estado, volvemos a empujar uno
                try { history.pushState(null, "", window.location.href); } catch (err) {}
                }
            }, false);

            // Bloquear atajos de recarga
            document.addEventListener("keydown", function (e) {
                if (e.key === "F5" || (e.ctrlKey && (e.key === "r" || e.key === "R")) || (e.metaKey && (e.key === "r" || e.key === "R"))) {
                e.preventDefault();
                // Puedes mostrar un pequeño toast en lugar de alert
                // alert("Recarga deshabilitada.");
                }
            });

            // Aviso al intentar cerrar/recargar (los navegadores modernos muestran su propio mensaje)
            window.addEventListener("beforeunload", function (e) {
                // Si quieres que no siempre pregunte, comenta la siguiente línea
                e.preventDefault();
                e.returnValue = ""; // necesario en muchos navegadores
                return ""; // compatibilidad
            });
            })();
*/
