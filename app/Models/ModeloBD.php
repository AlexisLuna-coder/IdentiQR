<?php
class BDModel {
    private $conn;

    //constructor de la clase
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /*
        !Método para generar el BACKUP de la base de datos.
        * Este método genera un archivo SQL con toda la estructura y contenido de la base de datos actual,
        * incluyendo tablas, datos, funciones, procedimientos almacenados y triggers.
        * Retorna: la ruta al archivo generado o false en caso de error.
     */
    public function backupDBs() {
        // Asigna la conexión actual 
        $conn = $this->conn;

        // Configura la zona horaria a Ciudad de México
        date_default_timezone_set('America/Mexico_City');
        $fechaHora = date('Y-m-d_H-i-s');
        
        //asignaciión del nombre al archivo
        $nombre = "Backup_IdentiQR_" . $fechaHora;
        $fileName = $nombre . ".sql";

        //Sirve para incluir la linea de "DROP TABLE" 
        $drop = true;

        // Si no hay conexión, cancela la función
        if (!$conn || $conn->connect_error) return false;

        // Consulta el nombre de la base de datos actual
        $bdRow = $conn->query("SELECT DATABASE() AS db");
        // Guarda el nombre de la BD 
        $bd = ($bdRow && $r = $bdRow->fetch_assoc()) ? $r['db'] : 'unknown';

        // Inicializa arreglos para guardar nombres de objetos de la BD
        $tablas = [];
        $funciones = [];
        $procedimientos = [];
        $triggers = [];

        // Obtiene la lista de todas las tablas
        $res = $conn->query("SHOW TABLES");
        //// Guarda cada nombre de tabla en el arreglo
        while ($row = $res->fetch_row()) $tablas[] = $row[0];

        // Obtiene la lista de funciones personalizadas de esta BD
        $resFunc = $conn->query("SHOW FUNCTION STATUS WHERE Db = DATABASE()");
        // Guarda los nombres de las funciones
        if ($resFunc) while ($row = $resFunc->fetch_assoc()) $funciones[] = $row['Name'];

        // Obtiene la lista de procedimientos almacenados y los guarda 
        $resProc = $conn->query("SHOW PROCEDURE STATUS WHERE Db = DATABASE()");
        if ($resProc) while ($row = $resProc->fetch_assoc()) $procedimientos[] = $row['Name'];

        // Obtiene la lista de disparadores (triggers) y los guarda
        $resTrig = $conn->query("SHOW TRIGGERS");
        if ($resTrig) while ($row = $resTrig->fetch_assoc()) $triggers[] = $row['Trigger'];

        // Recopila información del servidor y fechas para el encabezado
        $info['fecha'] = date("d-m-Y");
        $info['hora'] = date("h:i:s A");
        $info['mysqlver'] = $conn->server_info;
        $info['phpver'] = phpversion();

        // Crea una lista de tablas separadas por punto y coma
        $info['tablas'] = implode(";  ", $tablas);

        //PODRÁ CAMBIAR EL <<<EOT EOT; >>>
        // Inicia la variable $dump con un encabezado visual (comentarios)
        $dump = <<<EOT
            # +===================================================================
            # |
            # | Generado el {$info['fecha']} a las {$info['hora']}
            # | Servidor: {$_SERVER['HTTP_HOST']}
            # | MySQL Version: {$info['mysqlver']}
            # | PHP Version: {$info['phpver']}
            # | Base de datos: '{$bd}'
            # | Tablas: {$info['tablas']}
            # |
            # +-------------------------------------------------------------------
            EOT;
            
        // Agrega comando para desactivar chequeo de llaves foráneas temporalmente    
        $dump .= "\n\nSET FOREIGN_KEY_CHECKS=0;\n\n";
        // Configura el modo SQL para evitar errores con autoincrementables en 0
        $dump .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n\n";

        // Tablas
        foreach ($tablas as $tabla) {
            // Si $drop es true, agrega comando para borrar la tabla si existe
            if ($drop) $dump .= "DROP TABLE IF EXISTS `{$tabla}`;\n";
            
            // Crear la estructura de la tabla
            $resCreate = $conn->query("SHOW CREATE TABLE `{$tabla}`");
            // Agrega el "CREATE TABLE..." al archivo de respaldo
            if ($resCreate && $r = $resCreate->fetch_row()) $dump .= $r[1] . ";\n\n";

            // Obtiene todos los datos (registros) de la tabla
            $resData = $conn->query("SELECT * FROM `{$tabla}`");
            
            if ($resData) {
                // Recorre cada fila de datos
                while ($fila = $resData->fetch_assoc()) {
                    $values = [];
                    // Recorre cada columna de la fila
                    foreach ($fila as $val) {
                        $values[] = is_null($val) ? "NULL" : "'" . $conn->real_escape_string($val) . "'";
                    }
                    // Crea la sentencia INSERT con los datos procesados
                    $dump .= "INSERT INTO `{$tabla}` VALUES (" . implode(", ", $values) . ");\n";
                }
            }
            $dump .= "\n";
        }

        // Funciones, Procedimientos y Disparadores con DELIMITER funcional
        foreach ($funciones as $funcion) {
            if ($drop) $dump .= "DROP FUNCTION IF EXISTS `{$funcion}`;\n";
            $resCreate = $conn->query("SHOW CREATE FUNCTION `{$funcion}`");
            if ($resCreate && $r = $resCreate->fetch_row()) $dump .= "DELIMITER //\n" . $r[2] . "//\nDELIMITER ;\n\n";
        }

        foreach ($procedimientos as $proc) {
            if ($drop) $dump .= "DROP PROCEDURE IF EXISTS `{$proc}`;\n";
            $resCreate = $conn->query("SHOW CREATE PROCEDURE `{$proc}`");
            if ($resCreate && $r = $resCreate->fetch_row()) $dump .= "DELIMITER //\n" . $r[2] . "//\nDELIMITER ;\n\n";
        }

        foreach ($triggers as $trigger) {
            if ($drop) $dump .= "DROP TRIGGER IF EXISTS `{$trigger}`;\n";
            $resCreate = $conn->query("SHOW CREATE TRIGGER `{$trigger}`");
            if ($resCreate && $r = $resCreate->fetch_row()) $dump .= "DELIMITER //\n" . $r[2] . "//\nDELIMITER ;\n\n";
        }

        // Reactiva el chequeo de llaves foráneas al final del script
        $dump .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // Guardar el contenido en archivo físico
        $filePath = __DIR__ . '/../../config/Backups/' . $fileName;

        // En caso de no existir la carpeta de Backups, esta se creará
        if (!file_exists(dirname($filePath))) 
            mkdir(dirname($filePath), 0777, true);
        $success = file_put_contents($filePath, $dump);
        
        return $success ? $filePath : false;
    }

    /*
    ! Método para restaurar la base de datos a partir de un archivo SQL.
     *  Lee el archivo línea por línea, maneja correctamente los delimitadores y ejecuta las consultas.
     *  Es capaz de manejar cambios de DELIMITER (necesario para Procedures/Triggers).
     */ 
    public function restoreDBs($ruta) {
        $conn = $this->conn;
        $sentenciaSQL = file($ruta);
        if ($sentenciaSQL === false) 
            return "Error: No se pudo leer el archivo SQL.";
        // Deshabilitar temporalmente las llaves foráneas para evitar errores e insertar las tablas en cualquier orden
        $conn->query("SET FOREIGN_KEY_CHECKS=0");
        $queryAEjecutar = "";
        $delimiter = ";"; // El delimitador por defecto en SQL es punto y coma
        foreach ($sentenciaSQL as $line) {
            $trimLine = trim($line);
            // Evitar/ignoramos los comentarios y espacios
            if (empty($trimLine) || strpos($trimLine, "--") === 0 || strpos($trimLine, "#") === 0) {
                continue;
            }
            // !DETECCIÓN DE CAMBIO DE DELIMITER
            // Verificación de delimitador (Para cuando se inserte algún procedimientos/triggers/funciones)
            if (preg_match('/^DELIMITER\s+(\S+)/i', $trimLine, $coincidencia)) {
                $delimiter = $coincidencia[1]; // No ejecutamos esta línea, solo cambiamos la regla de parsing
                continue;
            }
            // Acumular en la consulta actual
            $queryAEjecutar .= $line;
            // Verificar si la línea termina con el delimitador actual para ejecutar la consulta por bloques
            if (preg_match('/' . preg_quote($delimiter, '/') . '\s*$/', $trimLine)) {
                // Quitamos el delimitador del final de la cadena para que MySQL la acepte
                $sqlToRun = substr(trim($queryAEjecutar), 0, -strlen($delimiter));
                if (!empty(trim($sqlToRun))) {
                    try {
                        $conn->query($sqlToRun);
                    } catch (mysqli_sql_exception $e) {
                        // En caso de error, reactivar las constraints FK y devolver error
                        $conn->query("SET FOREIGN_KEY_CHECKS=1");
                        return "Error SQL en restauración: " . $e->getMessage();
                    }
                }
                // Limpiamos la variable para la siguiente consulta
                $queryAEjecutar = ""; 
            }
        }
        // Reactivar las llaves foraneás que se tengan. SOLO AL ACABAR DE ESCRIBIR TODO EL SQL
        $conn->query("SET FOREIGN_KEY_CHECKS=1");
        return true;
    }
}
?>