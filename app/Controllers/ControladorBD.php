<?php
    require_once __DIR__ . '/../../config/Connection_BD.php';
    require_once __DIR__ . '/../Models/ModeloBD.php';

    class ControllerBD{
        private $modelBD;
        public function __construct($conn){
            $this->modelBD = new BDModel($conn);
        }


        /*FUNCIÓN PARA LA GENERACIÓN DEL BACKUP DE LA BASE DE DATOS */
        public function backupDBs(){

            //Validamos que se haya mandado
            //if(isset($_POST[''])){
            $backup = $this->modelBD->backupDBs();
            //}

            if($backup && file_exists($backup)){
                $nombreArch = basename($backup);
                header("Content-disposition: attachment; filename =".$nombreArch); //Vamos a crear un archivo NUEVO
                //Descargar el archivo y no ejecutarlo
                header("Content-type: MIME");
                //Leer el contenido del archivo y guardarlo
                readfile(__DIR__ . '/../../config/Backups/' . $nombreArch); //Busca el archivo dentro del directorio
            }
            
        }
        /*FUNCIÓN PARA LA GENERACIÓN DEL RESTORE DE LA BASE DE DATOS */
        public function restoreDBs(){

            /*NOTA. CONSIDERAR QUE EL Archivo cargado se solicitará */
            // Config
            $tamañoMaximoArch = 50 * 1024 * 1024; // 50 MB
            $uploadDir = realpath(__DIR__ . '/../../config/Uploads');

            //Si el fichero/directorio o carpeta aún no se crea. Se creará uno nuevo
            if (!is_dir($uploadDir)) 
                mkdir($uploadDir, 0755, true);

            // Validar que venga archivo
            if (!isset($_FILES['backupFile']) || $_FILES['backupFile']['error'] !== UPLOAD_ERR_OK) {

                //NOTA. Redirigir con un mensaje de error usando SweetAlert
                die("Error al subir el archivo. Código de error: " . ($_FILES['backupFile']['error'] ?? 'none'));
            }

            $file = $_FILES['backupFile'];
            $filename = basename($file['name']);
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 
            /*NOTA. pathinfo($filename, PATHINFO_EXTENSION): Está función extrae la extensión del nombre de archivo ($filename).
                Ejemplo: Si $filename es "Backup_IdentiQR_2025-11-12_18-31-54.sql", está parte devuelve "sql". 
            */
            
            //Valida que la extensión dea .sql
            if ($ext !== 'sql') {
                //NOTA. Considerar redirigir con un mensaje de error usando SweetAlert
                die("Solo se permiten archivos .sql");
            }
            //Valida que el tamaño del archivo no supere los 50MB
            if ($file['size'] > $tamañoMaximoArch) {
                //NOTA. Considerar redirigir con un mensaje de error usando SweetAlert
                die("Archivo demasiado grande. Máximo permitido: " . ($tamañoMaximoArch / 1024 / 1024) . " MB");
            }

            // Mover archivo a la carpeta de uploads
            $carpetaSistemaUpload = $uploadDir . '/' . uniqid('restore_', true) . '.sql'; //La función uniqid() de PHP se utiliza para generar un identificador único basado en la marca de tiempo actual en microsegundos. https://www.php.net/manual/es/function.uniqid.php
            
            
            //date_default_timezone_set('America/Mexico_City'); // Asignación de hora horaria
            //$fechaHora = date('Y-m-d_H-i-s'); //Año-Mes-Dia_Hora-Minutos-Segundos
            //$carpetaSistemaUpload = $uploadDir . '/' . $filename'_'.$fechaHora .'_restore_' . '.sql';
            if (!move_uploaded_file($file['tmp_name'], $carpetaSistemaUpload)) {
                //NOTA. ABAJO ESTÁ LA LIGA DE LO QUE FUNCIONA O COMO FUNCIONA
                //The move_uploaded_file() function in PHP is used to move an uploaded file from its temporary location on the server to a new, specified destination. This function is crucial for securely handling file uploads in web applications.
                
                //NOTA. Considerar redirigir con un mensaje de error usando SweetAlert
                die("No se pudo guardar el archivo en el servidor.");
            }

            //$ruta = (__DIR__ . '/../../config/Backups/'.$filename);

            // Llamar al modelo para realizar la restauración
            $restore = $this -> modelBD -> restoreDBs($carpetaSistemaUpload);

            // 5. Eliminar el archivo SQL temporal después de usarlo (por seguridad y limpieza) - CONSIDERAR BORRARLO
            /*if (file_exists($carpetaSistemaUpload)) {
                unlink($carpetaSistemaUpload);
            }
            */

            // 6. Gestionar la respuesta al usuario (SweetAlert o similar)
            // Aquí deberías incluir la vista y mostrar el mensaje SweetAlert
            if (strpos($restore, "Restauracion exitosa") !== false) {
                 // Éxito: Redirigir a la vista principal
                 // Nota: Esto debe ser gestionado por tu redireccionAcciones principal
                echo "<script>alert('Restauración exitosa'); window.location.href = '/IdentiQR/app/Views/GestionesAdministradorG.php';</script>";
            } else {
                // Error: Mostrar el mensaje detallado del error de restauración
                echo "<script>alert('Error en la restauración: $restore'); window.history.back();</script>";
            }

            //include_once "/../Views/GestionesAdministradorG.php";
        }

    }

?>