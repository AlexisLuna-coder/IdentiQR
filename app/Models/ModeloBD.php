<?php
class BDModel {
    private $conn;

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
        $conn = $this->conn;
        date_default_timezone_set('America/Mexico_City');
        $fechaHora = date('Y-m-d_H-i-s');
        $nombre = "Backup_IdentiQR_" . $fechaHora;
        $fileName = $nombre . ".sql";
        $drop = true;

        if (!$conn || $conn->connect_error) return false;

        $bdRow = $conn->query("SELECT DATABASE() AS db");
        $bd = ($bdRow && $r = $bdRow->fetch_assoc()) ? $r['db'] : 'unknown';

        $tablas = [];
        $funciones = [];
        $procedimientos = [];
        $triggers = [];

        $res = $conn->query("SHOW TABLES");
        while ($row = $res->fetch_row()) $tablas[] = $row[0];

        $resFunc = $conn->query("SHOW FUNCTION STATUS WHERE Db = DATABASE()");
        if ($resFunc) while ($row = $resFunc->fetch_assoc()) $funciones[] = $row['Name'];

        $resProc = $conn->query("SHOW PROCEDURE STATUS WHERE Db = DATABASE()");
        if ($resProc) while ($row = $resProc->fetch_assoc()) $procedimientos[] = $row['Name'];

        $resTrig = $conn->query("SHOW TRIGGERS");
        if ($resTrig) while ($row = $resTrig->fetch_assoc()) $triggers[] = $row['Trigger'];

        $info['fecha'] = date("d-m-Y");
        $info['hora'] = date("h:i:s A");
        $info['mysqlver'] = $conn->server_info;
        $info['phpver'] = phpversion();
        // Representación breve de tablas, funciones, procedimientos y triggers
        $info['tablas'] = implode(";  ", $tablas);

        //PODRÁ CAMBIAR EL <<<EOT EOT; >>>
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
        $dump .= "\n\nSET FOREIGN_KEY_CHECKS=0;\n\n";
        $dump .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n\n";

        //$dump = "-- Generado el {$info['fecha']} a las {$info['hora']}\n";
        //$dump .= "-- Servidor:  {$info['mysqlver']}\n";
        //$dump .= "SET FOREIGN_KEY_CHECKS=0;\n";
        //$dump .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n\n";

        // Tablas
        foreach ($tablas as $tabla) {
            if ($drop) $dump .= "DROP TABLE IF EXISTS `{$tabla}`;\n";
            $resCreate = $conn->query("SHOW CREATE TABLE `{$tabla}`");
            if ($resCreate && $r = $resCreate->fetch_row()) $dump .= $r[1] . ";\n\n";

            $resData = $conn->query("SELECT * FROM `{$tabla}`");
            if ($resData) {
                while ($fila = $resData->fetch_assoc()) {
                    $values = [];
                    foreach ($fila as $val) {
                        $values[] = is_null($val) ? "NULL" : "'" . $conn->real_escape_string($val) . "'";
                    }
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

        $dump .= "SET FOREIGN_KEY_CHECKS=1;\n";

        $filePath = __DIR__ . '/../../config/Backups/' . $fileName;
        if (!file_exists(dirname($filePath))) mkdir(dirname($filePath), 0777, true);
        $success = file_put_contents($filePath, $dump);
        
        return $success ? $filePath : false;
    }

    /*
    ! Método para restaurar la base de datos a partir de un archivo SQL.
     *  Lee el archivo línea por línea, maneja correctamente los delimitadores y ejecuta las consultas.
     *  
     */ 
    public function restoreDBs($ruta) {
        $conn = $this->conn;
        $sentenciaSQL = file($ruta);
        if ($sentenciaSQL === false) 
            return "Error: No se pudo leer el archivo SQL.";
        // Deshabilitar temporalmente las llaves foráneas para evitar errores
        $conn->query("SET FOREIGN_KEY_CHECKS=0");
        $queryAEjecutar = "";
        $delimiter = ";";
        foreach ($sentenciaSQL as $line) {
            $trimLine = trim($line);
            // Evitar/ignoramos los comentarios y espacios
            if (empty($trimLine) || strpos($trimLine, "--") === 0 || strpos($trimLine, "#") === 0) {
                continue;
            }
            // Verificación de delimitador (por ejemplo para procedimientos/triggers/funciones)
            if (preg_match('/^DELIMITER\s+(\S+)/i', $trimLine, $coincidencia)) {
                $delimiter = $coincidencia[1]; 
                continue;
            }
            // Acumular en la consulta actual
            $queryAEjecutar .= $line;
            // Verificar si la línea termina con el delimitador actual para ejecutar la consulta por bloques
            if (preg_match('/' . preg_quote($delimiter, '/') . '\s*$/', $trimLine)) {
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
                $queryAEjecutar = ""; 
            }
        }
        // Reactivar las llaves foraneás que se tengan. SOLO AL ACABAR DE ESCRIBIR TODO EL SQL
        $conn->query("SET FOREIGN_KEY_CHECKS=1");
        return true;
    }
}
?>