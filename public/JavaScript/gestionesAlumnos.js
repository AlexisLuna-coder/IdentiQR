/* Este archivo funcionara para el uso de JS de los Alumnos*/
//1. Se autoescribira el CORREO
// Espera a que todo el DOM se haya cargado
document.addEventListener("DOMContentLoaded", () => {
    const matriculaInput = document.getElementById("matricula");
    const correoInput = document.getElementById("correo");

    if (matriculaInput && correoInput) {
        matriculaInput.addEventListener("input", () => {
            const matricula = matriculaInput.value.trim();
            if (matricula !== "") {
                correoInput.value = matricula + "@upemor.edu.mx";
            } else {
                correoInput.value = "";
            }
        });
    }

    // Event listener para el botón de eliminación de alumno
    const btnEliminar = document.getElementById('BajaAlumno_EliminarAlumno');
    if (btnEliminar) {
        btnEliminar.addEventListener('click', function(e) {
            e.preventDefault(); // evitamos submit inmediato
            const form = document.getElementById('formBajaAlumno');

            Swal.fire({
                title: "¿Estás seguro de eliminar este alumno?",
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
                }
            });
        });
    }
});

/*2. MENSAJES DE NOTIFICACIONES */

function registroAlumno(){
    Swal.fire({
        title: "Registro exitoso",
        text: "Se registro adecuadamente. Su código QR fue enviado al correo registrado.",
        icon: "success",
        timer: 3000,
        showConfirmButton: false
    });
}


/*4. MENSAJES DE NOTIIFCACIONES DE ELIMINACIÓN */
function confirmacionEliminacionAlumno(event){
    event.preventDefault();  // Prevenir envío inmediato del form
    const inputCred = document.getElementById('idAlumno_BajaUSUARIO');
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
            document.getElementById('formBajaAlumno').submit();
        }
    });

    // Retornamos false para prevenir el submit normal
    return false;
}


/*1.1.1. Funciones para hacer uso de la camara dentro del modal y el escaneo*/
(() => {
    // Variables del escáner en ámbito del módulo
    let scanner = null;
    let video = null;
    let estado = null;
    let datosQR = null;
    let modal = null;
    let btnEscanear = null;

    // Esperar a que DOM esté listo
    document.addEventListener('DOMContentLoaded', () => {
        // Referencias a elementos (si no existen, guardamos null)
        video = document.getElementById('video');
        estado = document.getElementById('estado');
        datosQR = document.getElementById('datosQR');
        modal = document.getElementById('modalEscanear');
        btnEscanear = document.getElementById('btnEscanear');

        // Botón para abrir modal
        if (btnEscanear) {
            btnEscanear.addEventListener('click', (e) => {
                e.preventDefault();
                abrirModal();
            });
        }

        // Cerrar al hacer click en la X ya existente en el HTML (usa la función cerrarModal globalmente disponible)
        // Además cerramos si se hace click fuera del modal
        document.addEventListener('click', (ev) => {
            if (!modal) return;
            // click fuera del contenido del modal -> cerrar
            if (ev.target === modal) {
                cerrarModal();
            }
        });

        // Asegurar que ID de botón de eliminar coincida
        const btnEliminar = document.getElementById('BajaAlumno_EliminarUsuario');
        if (btnEliminar) {
            btnEliminar.addEventListener('click', (e) => {
                e.preventDefault();
                const form = document.getElementById('formBajaAlumno');
                Swal.fire({
                    title: "¿Estás seguro de eliminar este alumno?",
                    text: "¡No podrás revertir esta acción!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }

        // Si Instascan aún no está cargado (porque no pudiste cambiar orden), esperar un poco
        waitForInstascanThenInit();
    });
     // -------------------------
    // Funciones públicas/privadas
    // -------------------------
    function abrirModal() {
        if (!modal) {
            console.warn('Modal no encontrado (#modalEscanear).');
            return;
        }
        modal.style.display = 'block';
        iniciarScanner();
    }

    function cerrarModal() {
        if (!modal) return;
        modal.style.display = 'none';
        detenerScanner();
        if (datosQR) datosQR.innerHTML = '<p class="text-muted">Acerque el Código QR a escanear.</p>';
        if (estado) estado.innerHTML = '';
    }

    function iniciarScanner() {
        if (!video) {
            console.error('Video element (#video) no encontrado.');
            return;
        }

        // Si Instascan no existe todavía, we wait a little more (defensivo)
        if (typeof window.Instascan === 'undefined') {
            console.warn('Instascan no está disponible aún. Reintentando en 300ms...');
            setTimeout(iniciarScanner, 300);
            return;
        }

        // Crear scanner
        scanner = new Instascan.Scanner({ video: video });

        scanner.addListener('scan', function (content) {
            if (estado) estado.innerHTML = '<span class="text-success">QR detectado! Procesando...</span>';
            if (datosQR) datosQR.innerHTML = '<h6>Datos del QR:</h6><pre>' + escapeHtml(content) + '</pre>';
            detenerScanner(); // detenemos la cámara al detectar

            // Enviar al controlador para procesar
            fetch('/IdentiQR/app/Controllers/ControladorAlumnos.php?action=procesarQR', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'qrContent=' + encodeURIComponent(content)
            })
            .then(resp => resp.text())
            .then(data => {
                manejarRespuestaServidorQR(data, content);
            })
            .catch(err => {
                console.error('Error al procesar el QR:', err);
                if (datosQR) datosQR.innerHTML += '<br><span class="text-danger">Error al procesar el QR.</span>';
            });
        });

        // Obtener cámaras
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                let selectedCamera = cameras[0];
                if (isMobile) {
                    for (let cam of cameras) {
                        const n = (cam.name || '').toLowerCase();
                        if (n.includes('back') || n.includes('rear') || n.includes('trasera')) {
                            selectedCamera = cam;
                            break;
                        }
                    }
                    if (selectedCamera === cameras[0] && cameras.length > 1) {
                        selectedCamera = cameras[cameras.length - 1];
                    }
                }
                scanner.start(selectedCamera);
                if (estado) estado.innerHTML = 'Cámara activada. Escaneando...';
            } else {
                if (estado) estado.innerHTML = '<span class="text-danger">No se encontraron cámaras.</span>';
            }
        }).catch(function (e) {
            if (estado) estado.innerHTML = '<span class="text-danger">Error al acceder a la cámara: ' + escapeHtml(e.message) + '</span>';
        });
    }

    function detenerScanner() {
        if (scanner) {
            try {
                scanner.stop();
            } catch (e) {
                // algunos navegadores pueden lanzar si ya está parado
            }
            scanner = null;
        }
    }

    function manejarRespuestaServidorQR(data, content) {
        if (!datosQR) 
            return;

        if (data.includes('redirect:')) {
            const url = data.split('redirect:')[1];
            datosQR.innerHTML += '<br><p>¿Desea proceder con la redirección a Modificación?</p>';
            datosQR.innerHTML += '<button id="aceptarBtn" class="btn btn-success btn-sm">Aceptar</button> ';
            datosQR.innerHTML += '<button id="cancelarBtn" class="btn btn-danger btn-sm">Cancelar</button> ';
            datosQR.innerHTML += '<button id="usarMatriculaBtn" class="btn btn-info btn-sm">Solamente usar Matricula</button>';

            // Listeners para estos botones (se añaden dinámicamente)
            document.getElementById('aceptarBtn').addEventListener('click', function() {
                window.location.href = url;
            });
            document.getElementById('cancelarBtn').addEventListener('click', function() {
                cerrarModal();
            });
            document.getElementById('usarMatriculaBtn').addEventListener('click', function() {
                const lines = content.split('\n');
                const matriculaLine = lines.find(line => line.startsWith('Matricula:'));
                if (matriculaLine) {
                    const matricula = matriculaLine.split(':')[1].trim();
                    const input = document.getElementById('idAlumno_BajaUSUARIO');
                    if (input) input.value = matricula;
                }
                cerrarModal();
            });
        } else {
            datosQR.innerHTML += '<br><span class="text-danger">' + escapeHtml(data) + '</span>';
        }
    }

    // Escapa texto simple para mostrarse en HTML evitando problemas con caracteres
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Si Instascan puede tardar en cargarse, lo detectamos y reiniciamos el init
    function waitForInstascanThenInit() {
        if (typeof window.Instascan !== 'undefined') {
            // listo (no hacemos nada aquí; iniciarScanner se llama cuando abres modal)
            return;
        }
        // reintentar hasta cierto número de veces
        let tries = 0;
        const maxTries = 30; // ~9 segundos
        const interval = setInterval(() => {
            tries++;
            if (typeof window.Instascan !== 'undefined') {
                clearInterval(interval);
            } else if (tries >= maxTries) {
                clearInterval(interval);
                console.warn('Instascan no cargó después de varios intentos. Verifica el orden de tus <script>.');
            }
        }, 300);
    }

    // Limpiar cámara si el usuario cierra/recarga la página
    window.addEventListener('beforeunload', () => {
        detenerScanner();
    });

    // (Opcional) Exponer funciones por si quieres llamarlas desde HTML inline (p. ej. cerrarModal)
    window.cerrarModal = cerrarModal;
    window.abrirModal = abrirModal;

})(); // fin del IIFE