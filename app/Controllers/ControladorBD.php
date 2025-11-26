<?php
    require_once __DIR__ . '/../../config/Connection_BD.php';
    require_once __DIR__ . '/../Models/ModeloBD.php';

    /*Creamos la CLASE ControllerBD que será la encargada de tener todos los métodos para realizar los respaldos y restauraciones de la BD*/
    class ControllerBD{
        private $modelBD;
        public function __construct($conn){
            $this->modelBD = new BDModel($conn);
        }

        /*FUNCIÓN PARA LA GENERACIÓN DEL BACKUP DE LA BASE DE DATOS */
        /* 
         * FUNCIÓN PARA LA GENERACIÓN DEL BACKUP DE LA BASE DE DATOS
         * Esta función utiliza el modelo para crear un archivo de respaldo (Backup) de la base de datos.
         * Fuerza la descarga inmediata en el navegador del usuario.
         */
        public function backupDBs(){
            // Se llama al método del modelo que genera el backup y devuelve la ruta del archivo físico almacenada en el sistema
            $backup = $this->modelBD->backupDBs();

            // Verificamos que el backup se generó correctamente y que el archivo existe
            if($backup && file_exists($backup)){
                $nombreArch = basename($backup);
                // Se envían cabeceras para descargar el archivo generado, no para mostrarlo en el navegador
                // Indica al navegador que es un archivo adjunto (no abrir en pestaña nueva)
                header("Content-disposition: attachment; filename =".$nombreArch); // nombre del archivo a descargar
                header("Content-type: MIME"); // tipo MIME genérico para descarga
                // Se lee y envía el contenido del archivo al navegador
                readfile(__DIR__ . '/../../config/Backups/' . $nombreArch);
                // !Si se descomenta se podrá eliminar archivo después de descargar y ahorrar espacio en servidor
                // unlink($backup); 
                exit;
            }
        }

        /*FUNCIÓN PARA LA GENERACIÓN DEL RESTORE DE LA BASE DE DATOS (Valida el tamaño del archivo, la ext .sql y la llamada al metodo*/
        public function restoreDBs(){
            $statusAlert = null; // Variable para controlar la alerta en la vista (SweetAlert)

            /* NOTA. CONSIDERAR QUE EL Archivo cargado se solicitará */
            // Configuración para que el archivo no supere los 52,428,800 bytes
            $tamanoMaximoArch = 50 * 1024 * 1024; // 50 MB
            $uploadDir = realpath(__DIR__ . '/../../config/Uploads');

            // Realizamos la validación que se haya subido un archivo sin errores
            if (!isset($_FILES['backupFile']) || $_FILES['backupFile']['error'] !== UPLOAD_ERR_OK) {
                $errorCodigo = $_FILES['backupFile']['error'] ?? 'desconocido';
                $statusAlert = 'restore_error::Error al subir archivo (Código ' . ($_FILES['backupFile']['error'] ?? 'desc') . ')';
                $this->cargarVista($statusAlert);
                return; // Detener ejecución
            }
            $file = $_FILES['backupFile'];
            $filename = basename($file['name']);
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));  //Devuelve el formato de la extensión en minúsculas
            
            // Valida que la extensión sea .sql
            if ($ext !== 'sql') {
                $statusAlert = 'restore_error::Solo se permiten archivos .sql';
                $this->cargarVista($statusAlert); //Envia el mensaje de status a la vista. Ahí se mostrará
                return;
            }
            // Valida el tamaño (Max 50MB). Si se pasa NO PERMITE
            if ($file['size'] > $tamanoMaximoArch) {
                $maxMB = $tamanoMaximoArch / 1024 / 1024;
                $statusAlert = 'restore_error::El archivo excede el límite de 50MB';
                $this->cargarVista($statusAlert); //Envia el mensaje de status a la vista. Ahí se mostrará
                return;
            }
            // Se mueve el archivo (tomandolo como temporal) dentro de /config/Uploads/identificadorUnico.sql
            $carpetaSistemaUpload = $uploadDir . '/' . uniqid('restore_', true) . '.sql';
            if (!move_uploaded_file($file['tmp_name'], $carpetaSistemaUpload)) { /*https://www.php.net/manual/es/function.move-uploaded-file.php*/
                $statusAlert = 'restore_error::No se pudo guardar el archivo temporal'; //Envia el mensaje de status a la vista. Ahí se mostrará
                $this->cargarVista($statusAlert);
                return;
            }
            // Llamar al modelo para realizar la restauración ejecutando el Método inicial
            $restore = $this->modelBD->restoreDBs($carpetaSistemaUpload);
            // Eliminar el archivo SQL temporal para no GUARDARLO en el sistema; NO ES RELEVANTE
            if (file_exists($carpetaSistemaUpload)) {
                unlink($carpetaSistemaUpload);
            }
            //Definir mensaje de éxito o error
            if ($restore === true) {
                // ÉXITO: Redirigir a la vista principal o mostrar mensaje
                $statusAlert = 'restore_success';
            } else {
                // ERROR: Mostrar el mensaje detallado que vino del modelo
                $statusAlert = 'Error de restauración::' . $restore;
            }
            $this->cargarVista($statusAlert);
        }
        // Método auxiliar para cargar la vista y evitar repetir código
        private function cargarVista($statusAlert = null) {
            // Incluimos la vista principal del administrador
            include_once __DIR__ . '/../Views/GestionesAdministradorG.php';
        }
    }
?>