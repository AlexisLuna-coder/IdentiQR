<?php
    class BDModel{
        private $conn;
        //Construcción del contructor del modelo
        public function __construct($conn){
            $this->conn = $conn;
        }


        /*PARA LLAMAR A LOS MÉTODOS DEL BACKUP Y ASÍ */
        /* MÉTODO ORIGINAL DE backup.php
        public function backupDBs(){
            $conn = $this->conn;
            // Opciones para la configuración y generación de la BD respaldada
            date_default_timezone_set('America/Mexico_City'); //Asignación de hora horaria
            $fechaHora = date('Y-m-d_H-i-s'); //Año-Mes-Dia_Hora-Minutos-Segundos
            
            //$nombre = "Backup_IdentiQR_".$fechaHora.".sql"; // archivo a generar
            $nombre = "Backup_IdentiQR_".$fechaHora; // archivo a generar
            $drop = false;                   // si true incluye DROP TABLE IF EXISTS
            $tablas = false;                 // false = todas las tablas
            $compresion = false;             // "gz", "bz2" o false

            // Obtener conexión desde tu clase
            //$db = new Connection_BD();
            //$conn = $db->getConnection();
            if (!$conn || $conn->connect_error) {
                die("Error en la conexión: " . ($conn->connect_error ?? 'desconocido'));
            }

            // Obtener nombre de la base (si no lo tienes)
            $bdRow = $conn->query("SELECT DATABASE() AS db");
            $bd = ($bdRow && $r = $bdRow->fetch_assoc()) ? $r['db'] : 'unknown';

            // Obtener lista de tablas
            if (empty($tablas)) {
                $tablas = [];
                $res = $conn->query("SHOW TABLES");
                if (!$res) {
                    die("Error al obtener tablas: " . $conn->error);
                }
                while ($row = $res->fetch_row()) {
                    $tablas[] = $row[0];
                }
            }

            // Cabecera del dump
            $info['dumpversion'] = "1.1b";
            $info['fecha'] = date("d-m-Y");
            $info['hora'] = date("h:i:s A");
            $info['mysqlver'] = $conn->server_info;
            $info['phpver'] = phpversion();

            // Representación breve de tablas
            $info['tablas'] = implode(";  ", $tablas);

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

            foreach ($tablas as $tabla) {
                // DROP TABLE
                if ($drop) {
                    $drop_table_query = "DROP TABLE IF EXISTS `{$tabla}`;\n";
                } else {
                    $drop_table_query = "# DROP TABLE not specified.\n";
                }

                // CREATE TABLE
                $create_table_query = "";
                $resCreate = $conn->query("SHOW CREATE TABLE `{$tabla}`");
                if ($resCreate && $r = $resCreate->fetch_row()) {
                    $create_table_query = $r[1] . ";\n";
                } else {
                    $create_table_query = "# Error obteniendo estructura: " . $conn->error . "\n";
                }

                // INSERTs
                $insert_into_query = "";
                $resData = $conn->query("SELECT * FROM `{$tabla}`");
                if ($resData) {
                    while ($fila = $resData->fetch_assoc()) {
                        $values = [];
                        foreach ($fila as $val) {
                            if (is_null($val)) {
                                $values[] = "NULL";
                            } else {
                                // Escapamos y envolvemos en comillas simples
                                $values[] = "'" . $conn->real_escape_string($val) . "'";
                            }
                        }
                        $insert_into_query .= "INSERT INTO `{$tabla}` VALUES (" . implode(", ", $values) . ");\n";
                    }
                } else {
                    $insert_into_query = "# Error obteniendo datos: " . $conn->error . "\n";
                }

                $dump .= "\n# | Vaciado de tabla '{$tabla}'\n";
                $dump .= "# +------------------------------------->\n";
                $dump .= $drop_table_query . "\n";
                $dump .= "# | Estructura de la tabla '{$tabla}'\n";
                $dump .= "# +------------------------------------->\n";
                $dump .= $create_table_query . "\n";
                $dump .= "# | Carga de datos de la tabla '{$tabla}'\n";
                $dump .= "# +------------------------------------->\n";
                $dump .= $insert_into_query . "\n";
            }

            $dump .= "\nSET FOREIGN_KEY_CHECKS=1;\n";

            // Envío al navegador (download) o imprimir si headers ya enviados
            if (!headers_sent()) {
                header("Pragma: no-cache");
                header("Expires: 0");
                header("Content-Transfer-Encoding: binary");
                //PARA MANDAR A LAS DESCARGAS
                header("Content-Disposition: attachment; filename={$nombre}.sql"); //Vamos a crear un archivo NUEVO
                header("Content-type: application/sql");
                readfile("/IdentiQR/config/Backups/".$nombre.".sql");
                echo $dump;
                
                //switch ($compresion) {
                //    case "gz":
                //        header("Content-Disposition: attachment; filename={$nombre}.sql.gz");
                //        header("Content-type: application/x-gzip");
                //        echo gzencode($dump, 9);
                //        break;
                //    case "bz2":
                //        header("Content-Disposition: attachment; filename={$nombre}.sql.bz2");
                //        header("Content-type: application/x-bzip2");
                //        echo bzcompress($dump, 9);
                //        break;
                //    default:
                //        header("Content-Disposition: attachment; filename={$nombre}.sql");
                //        header("Content-type: application/sql");
                //        //readfile("/IdentiQR/config/Backups/".$nombre.".sql");
                //        echo $dump;
                //}
                
            } else {
                echo "<b>ATENCIÓN: Probablemente ha ocurrido un error - headers ya fueron enviados</b><br />\n<pre>\n$dump\n</pre>";
            }

            $conn->close();
            exit;
        }
        */
        public function backupDBs(){
            $conn = $this->conn;
            // Opciones para la configuración y generación de la BD respaldada
            date_default_timezone_set('America/Mexico_City'); // Asignación de hora horaria
            $fechaHora = date('Y-m-d_H-i-s'); //Año-Mes-Dia_Hora-Minutos-Segundos
            
            $nombre = "Backup_IdentiQR_".$fechaHora; // Nombre base del archivo a generar
            $fileName = $nombre . ".sql"; // Nombre completo del archivo

            $drop = true;                          // si true incluye DROP TABLE IF EXISTS

            // DROP TABLE
            $tablas = false;                        // false = todas las tablas
            // La compresión se manejará en el controlador si es necesario.

            // Obtener conexión desde tu clase
            if (!$conn || $conn->connect_error) {
                // Devolver false en lugar de morir, dejando la gestión de errores al controlador
                return false; 
            }

            // Obtener nombre de la base (si no lo tienes)
            $bdRow = $conn->query("SELECT DATABASE() AS db");
            $bd = ($bdRow && $r = $bdRow->fetch_assoc()) ? $r['db'] : 'unknown';

            // Obtener lista de tablas y funciones
            if (empty($tablas)) {
                $tablas = [];
                $res = $conn->query("SHOW TABLES");
                if (!$res) {
                    return false; // Error al obtener tablas
                }
                while ($row = $res->fetch_row()) {
                    $tablas[] = $row[0];
                }

                // Agregar funciones, procedimientos y triggers a la lista de elementos a respaldar
                $funciones = [];
                $resFunc = $conn->query("SHOW FUNCTION STATUS WHERE Db = DATABASE()");
                if ($resFunc) {
                    while ($row = $resFunc->fetch_assoc()) {
                        $funciones[] = $row['Name'];
                    }
                }

                $procedimientos = [];
                $resProc = $conn->query("SHOW PROCEDURE STATUS WHERE Db = DATABASE()");
                if ($resProc) {
                    while ($row = $resProc->fetch_assoc()) {
                        $procedimientos[] = $row['Name'];
                    }
                }

                $triggers = [];
                $resTrig = $conn->query("SHOW TRIGGERS");
                if ($resTrig) {
                    while ($row = $resTrig->fetch_assoc()) {
                        $triggers[] = $row['Trigger'];
                    }
                }
            }

            // Cabecera del dump
            $info['dumpversion'] = "1.1b";
            $info['fecha'] = date("d-m-Y");
            $info['hora'] = date("h:i:s A");
            $info['mysqlver'] = $conn->server_info;
            $info['phpver'] = phpversion();

            // Representación breve de tablas, funciones, procedimientos y triggers
            $info['tablas'] = implode(";  ", $tablas);
            $info['funciones'] = implode(";  ", $funciones);
            $info['procedimientos'] = implode(";  ", $procedimientos);
            $info['triggers'] = implode(";  ", $triggers);

            $dump = <<<EOT
            # +===================================================================
            # |
            # | Generado el {$info['fecha']} a las {$info['hora']}
            # | Servidor: {$_SERVER['HTTP_HOST']}
            # | MySQL Version: {$info['mysqlver']}
            # | PHP Version: {$info['phpver']}
            # | Base de datos: '{$bd}'
            # | Tablas: {$info['tablas']}
            # | Funciones: {$info['funciones']}
            # | Procedimientos: {$info['procedimientos']}
            # | Triggers: {$info['triggers']}
            # |
            # +-------------------------------------------------------------------
            EOT;
            $dump .= "\n\nSET FOREIGN_KEY_CHECKS=0;\n\n";

            // Agregar funciones
            foreach ($funciones as $funcion) {
                $create_function_query = "";
                $resFuncCreate = $conn->query("SHOW CREATE FUNCTION `{$funcion}`");
                if ($resFuncCreate && $r = $resFuncCreate->fetch_row()) {
                    $create_function_query = "DELIMITER //\n" . $r[2] . "//\nDELIMITER ;\n";
                } else {
                    $create_function_query = "# Error obteniendo función: " . $conn->error . "\n";
                }

                $dump .= "\n# | Función '{$funcion}'\n";
                $dump .= "# +------------------------------------->\n";
                $dump .= $create_function_query . "\n";
            }

            // Agregar procedimientos
            foreach ($procedimientos as $procedimiento) {
                $create_procedure_query = "";
                $resProcCreate = $conn->query("SHOW CREATE PROCEDURE `{$procedimiento}`");
                if ($resProcCreate && $r = $resProcCreate->fetch_row()) {
                    $create_procedure_query = "DELIMITER //\n" . $r[2] . "//\nDELIMITER ;\n";
                } else {
                    $create_procedure_query = "# Error obteniendo procedimiento: " . $conn->error . "\n";
                }

                $dump .= "\n# | Procedimiento '{$procedimiento}'\n";
                $dump .= "# +------------------------------------->\n";
                $dump .= $create_procedure_query . "\n";
            }

            // Agregar triggers
            foreach ($triggers as $trigger) {
                $create_trigger_query = "";
                $resTrigCreate = $conn->query("SHOW CREATE TRIGGER `{$trigger}`");
                if ($resTrigCreate && $r = $resTrigCreate->fetch_row()) {
                    $create_trigger_query = $r[2] . ";\n";
                } else {
                    $create_trigger_query = "# Error obteniendo trigger: " . $conn->error . "\n";
                }

                $dump .= "\n# | Trigger '{$trigger}'\n";
                $dump .= "# +------------------------------------->\n";
                $dump .= $create_trigger_query . "\n";
            }

            foreach ($tablas as $tabla) {
                // DROP TABLE
                if ($drop) {
                    $drop_table_query = "DROP TABLE IF EXISTS `{$tabla}`;\n";
                } else {
                    $drop_table_query = "# DROP TABLE not specified.\n";
                }

                // CREATE TABLE
                $create_table_query = "";
                $resCreate = $conn->query("SHOW CREATE TABLE `{$tabla}`");
                if ($resCreate && $r = $resCreate->fetch_row()) {
                    $create_table_query = $r[1] . ";\n";
                } else {
                    $create_table_query = "# Error obteniendo estructura: " . $conn->error . "\n";
                }

                // INSERTs
                $insert_into_query = "";
                $resData = $conn->query("SELECT * FROM `{$tabla}`");
                if ($resData) {
                    while ($fila = $resData->fetch_assoc()) {
                        $values = [];
                        foreach ($fila as $val) {
                            if (is_null($val)) {
                                $values[] = "NULL";
                            } else {
                                // Escapamos y envolvemos en comillas simples
                                $values[] = "'" . $conn->real_escape_string($val) . "'";
                            }
                        }
                        $insert_into_query .= "INSERT INTO `{$tabla}` VALUES (" . implode(", ", $values) . ");\n";
                    }
                } else {
                    $insert_into_query = "# Error obteniendo datos: " . $conn->error . "\n";
                }

                $dump .= "\n# | Vaciado de tabla '{$tabla}'\n";
                $dump .= "# +------------------------------------->\n";
                $dump .= $drop_table_query . "\n";
                $dump .= "# | Estructura de la tabla '{$tabla}'\n";
                $dump .= "# +------------------------------------->\n";
                $dump .= $create_table_query . "\n";
                $dump .= "# | Carga de datos de la tabla '{$tabla}'\n";
                $dump .= "# +------------------------------------->\n";
                $dump .= $insert_into_query . "\n";
            }

            $dump .= "\nSET FOREIGN_KEY_CHECKS=1;\n";

            // --- LÓGICA DE GUARDADO (Modelo: SÓLO escribe) ---
            $filePath = __DIR__ . '/../../config/Backups/' . $fileName;
            
            $success = file_put_contents($filePath, $dump);

            $conn->close();
            
            // Retorna la ruta completa del archivo para que el controlador pueda usarla
            return $success ? $filePath : false;
        }

        /*PARA LLAMAR A LOS MÉTODOS DEL RESTORE */
        public function restoreDBs($ruta){
            $conn = $this->conn;
            
            //file_get_contets guarda todos los datos para luego guardarlos
            // Leer el contenido del archivo SQL
            $query_archivo = file_get_contents($ruta);

            if ($query_archivo === false) {
                return "Error: No se pudo leer el archivo SQL";
            }


            // Desactivar verificaciones de claves foráneas temporalmente
            $conn->query("SET FOREIGN_KEY_CHECKS=0;");
            //lo que hace es tener varios quety por punyo y coma. Ejecutar múltiples consultas
            if($conn -> multi_query($query_archivo)){
                do{
                    //validar que haya resultados guardados
                    if($result = $conn -> store_Result()){
                        //almacena los resultados en store_result sirve para eso

                        //Liberar la memoria de los resultados que obtiene
                        $result -> free();
                    }
                }while($conn->more_results() && $conn->next_result());
                //ve que hasta que ya haya recorrido todo este do while
                //next_result() ve que si  hay otro por delante
                
                //devolver mensajes como por ejemplo en js

                // Reactivar verificaciones de claves foráneas
                $conn->query("SET FOREIGN_KEY_CHECKS=1;");

                return "Restauracion exitosa :D";
            } else {
                return "Error en la restauración" . $conn->errno;
            }
        }
    }
?>