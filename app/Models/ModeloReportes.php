<?php
    /*
     * Esta clase se encarga de todas las interacciones con la base de datos
     * necesarias para la generación de reportes y gráficas estadísticas.
     * Centraliza las consultas SQL complejas, JOINS y llamadas a Procedimientos Almacenados.
     * --------------------------------------------------------------------------
    */
    class ReportModel{
        private $conn;
        public function __construct($conn){
            $this->conn = $conn;
        }
        /*Apatir de acá se encontrarán las funciones para Generar los reportes para cada dirección.*/
        //idDepto = 1; Administrador general - Sin dirección asociada
        public function reporteGeneral_Admin(){
            $statement_query = "SELECT registroservicio.*,serviciotramite.Descripcion as Descripcion, serviciotramite.idDepto as idDepto, departamento.Nombre as NombreDepto FROM registroservicio 
            inner join serviciotramite on registroservicio.idTramite = serviciotramite.idTramite inner join departamento on servicioTramite.idDepto = departamento.idDepto;";    
            $result = $this->conn->query($statement_query);
            $data = []; //Declaramos el arreglo vacio para los datos que se encuentran en la consulta
            //Hacemos el ciclo que recuperará la información y generar un arreglo asociativo dentro de un arreglo. Arreglos dentro de arreglos
            while($row = $result ->fetch_assoc()){
                //Arreglos dentro de arreglos
                $data[] = [$row['FolioRegistro'], $row['Matricula'],$row['idTramite'],$row['FechaHora'],$row['descripcion'],$row['estatusT'],$row['FolioSeguimiento'],
                $row['Descripcion'],(int)$row['idDepto'], $row['NombreDepto']];
            }
            //Retornamos el arreglo
            return $data;
        }

        public function reporteGeneral2_Admin_Pastel(){
            //Creamos la sentencia en SQL para realizar un conteo de todos los departamentos (como estan registrados)
            $sql_statement = "select departamento.Nombre, count(*) as Total FROM USUARIO 
            INNER JOIN departamento ON usuario.idDepto = departamento.idDepto group by usuario.idDepto;";
            $result = $this -> conn -> query($sql_statement);
            $data = []; //Variable de tipo arreglo vacio para los datos que se encuentran en la consulta
            //Hacemos el ciclo que recuperará la información y generar un arreglo asociativo dentro de un arreglo. Arreglos dentro de arreglos
            while ($row = $result -> fetch_assoc()){ //Realizamos el ciclo y lo vamos almacenando dentro de nuestra variable. 
                //Arreglos dentro de arreglos
                $data[] = [$row['Nombre'], (int) $row['Total']];
            }
            //Retornamos el arreglo
            return $data;
        }
        public function reporteGeneral2_Datos(){
            $sql_statement = "SELECT usuario.*, departamento.Nombre as NombreDepto FROM USUARIO INNER JOIN departamento ON usuario.idDepto = departamento.idDepto;";
            $result = $this -> conn -> query($sql_statement);
            $data = []; //Variable de tipo arreglo vacio para los datos que se encuentran en la consulta
            //Hacemos el ciclo que recuperará la información y generar un arreglo asociativo dentro de un arreglo. Arreglos dentro de arreglos
            while ($row = $result -> fetch_assoc()){
                //Arreglos dentro de arreglos
                $data[] = [$row['id_usuario'], $row['nombre'], $row['apellido_paterno'], $row['apellido_materno'], $row['genero'],$row['usr'],$row['email'],$row['passw'],$row['rol'],(int)$row['idDepto'],$row['FechaRegistro'],$row['NombreDepto']];
            }
            //Retornamos el arreglo
            return $data;
        
        }

        public function reporteGeneral3_AlumnosAdmin_Pastel(){
            $sql_statement = "SELECT descripcion, count(*) as Total from alumno inner join carrera on alumno.idCarrera = carrera.idCarrera group by alumno.idCarrera;";
            $result = $this -> conn -> query($sql_statement);
            $data = []; //Variable de tipo arreglo vacio para los datos que se encuentran en la consulta
            //Hacemos el ciclo que recuperará la información y generar un arreglo asociativo dentro de un arreglo. Arreglos dentro de arreglos
            while ($row = $result -> fetch_assoc()){
                //Arreglos dentro de arreglos
                $data[] = [$row['descripcion'], (int) $row['Total']];
            }
            //Retornamos el arreglo
            return $data;
            
        }
        public function reporteGeneral3_Datos(){
            $sql_statement = "SELECT alumno.*, carrera.descripcion as DesCarr,carrera.planEstudios as PlanEst  from alumno inner join carrera on alumno.idCarrera = carrera.idCarrera;";
            $result = $this -> conn -> query($sql_statement);
            $data = []; //Variable de tipo arreglo vacio para los datos que se encuentran en la consulta
            //Hacemos el ciclo que recuperará la información y generar un arreglo asociativo dentro de un arreglo. Arreglos dentro de arreglos
            while ($row = $result -> fetch_assoc()){
                $data[] = [$row['Matricula'], $row['Nombre'],$row['ApePat'],$row['ApeMat'],$row['FechaNac'],$row['FeIngreso'],$row['Correo'],$row['Direccion'],$row['Telefono'],$row['Ciudad'],$row['Estado'],$row['Genero'],$row['idCarrera'],$row['qrHash'],$row['fechaGenerarQR_1'],$row['fechaExpiracionQR_2'],$row['DesCarr'],$row['PlanEst']];
            }
            //Retornamos el arreglo
            return $data;
        }

        //idDepto = 2; Dirección acádemica
        // *Agrupa y cuenta los trámites realizados específicamente en este departamento.
        public function reporteGeneral_DirAca_Pastel(){
            //Realiza el sql statement
            $sql_statement = "SELECT st.descripcion AS DescripcionT, COUNT(*) AS Total
                FROM registroservicio rs
                INNER JOIN serviciotramite st ON rs.idTramite = st.idTramite
                WHERE st.idDepto = 2
                GROUP BY st.descripcion
            ";
            $result = $this -> conn -> query($sql_statement);
            $data = []; //Declaramos el arreglo vacio para los datos que se encuentran en la consulta
            while ($row = $result -> fetch_assoc()){
                $data[] = [$row['DescripcionT'], (int) $row['Total']];
            }
            return $data; // Retornamos el arreglo
        }
        //idDepto = 3; Servicios escolares
        public function reporteGeneral_ServEsco_Pastel(){
            $sql_statement = "SELECT st.descripcion AS DescripcionT, COUNT(*) AS Total
                FROM registroservicio rs
                INNER JOIN serviciotramite st ON rs.idTramite = st.idTramite
                WHERE st.idDepto = 3
                GROUP BY st.descripcion
            ";
            $result = $this -> conn -> query($sql_statement);
            $data = []; //Declaramos el arreglo vacio para los datos que se encuentran en la consulta
            while ($row = $result -> fetch_assoc()){
                $data[] = [$row['DescripcionT'], (int) $row['Total']];
            }
            return $data;
        }

        public function reporteGeneral_ServEsco_Datos(){
            $statement_query = "SELECT registroservicio.*,serviciotramite.Descripcion as Descripcion, serviciotramite.idDepto as idDepto, departamento.Nombre as NombreDepto FROM registroservicio inner join serviciotramite on registroservicio.idTramite = serviciotramite.idTramite inner join departamento on servicioTramite.idDepto = departamento.idDepto where serviciotramite.idDepto = 3;";    

            $result = $this->conn->query($statement_query);
            
            $data = []; //Declaramos el arreglo vacio para los datos que se encuentran en la consulta

            //Hacemos el ciclo que recuperará la información
            while($row = $result ->fetch_assoc()){
                //Arreglos dentro de arreglos
                $data[] = [$row['FolioRegistro'], $row['Matricula'],$row['idTramite'],$row['FechaHora'],$row['descripcion'],$row['estatusT'],$row['FolioSeguimiento'],$row['Descripcion'],(int)$row['idDepto'], $row['NombreDepto']];
            }
            //Retornamos el arreglo
            return $data;
        }
        //idDepto = 4; Dirección Desarrollo Academico
        //idDepto = 5; Dirección Asuntos Estudiantiles
        public function reporteGeneral_DAE($fe1,$fe2, $idDepto){
            $sql_statement = "SELECT *FROM registroservicio rs inner join serviciotramite st on rs.idTramite = st.idTramite where date(FechaHora) BETWEEN ? AND ? and idDepto = ?";
            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                error_log("Connection_BD->reportePorDia_DirMed prepare error: " . $this->conn->error);
                return false;
            }
            // Forzar la cinversión (parsing)
            $idD = (int)$idDepto;
            // Bind seguro
            if (!$stmt->bind_param("ssi", $fe1,$fe2, $idD)) {
                error_log("Connection_BD->reportePorDia_DirMed bind_param error: " . $stmt->error);
                $stmt->close();
                return false;
            }
            $stmt->bind_param("ssi", $fe1,$fe2, $idD);
            if (!$stmt->execute()) {
                // Manejo de error si falla la ejecución
                throw new Exception("Error en execute(): " . $statement->error);
            }
            
            // Obtener resultado (requiere mysqlnd)
            $result = $stmt->get_result();
            if ($result === false || $result === null) {
                // No hay result set o get_result no está disponible
                $stmt->close();
                return false;
            }

            // Retornamos el result
            return $result;
        }
        //idDepto = 6; Consultorio de atención de primer contacto
        public function reporteInd_DirMed($consultaReporte,$fe1,$fe2,$gen, $idDepto){
            // Preparar la llamada
            $sql = "CALL reporteInd_DirMed(?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                error_log("Connection_BD->reporteInd_DirMed prepare error: " . $this->conn->error);
                return false;
            }
            // Forzar tipos
            $opc = (int)$consultaReporte;
            $idD = (int)$idDepto;
            // Bind seguro
            if (!$stmt->bind_param("isssi", $opc, $fe1, $fe2, $gen, $idD)) {
                error_log("Connection_BD->reporteInd_DirMed bind_param error: " . $stmt->error);
                $stmt->close();
                return false;
            }

            if (!$stmt->execute()) {
                error_log("Connection_BD->reporteInd_DirMed execute error: " . $stmt->error);
                $stmt->close();
                return false;
            }

            $allSets = [];

            do {
                $rows = [];

                if (method_exists($stmt, 'get_result')) {
                    $res = $stmt->get_result();
                    if ($res !== false && $res !== null) {
                        // fetch_all si hay filas
                        $rows = $res->fetch_all(MYSQLI_ASSOC);
                        $res->free();
                    } else {
                        // dejamos $rows = [] para mantener consistencia de sets
                        $rows = [];
                    }
                } else {
                    $rows = [];
                }

                $allSets[] = $rows;
            } while ($stmt->more_results() && $stmt->next_result());
            // Cerrar stmt
            $stmt->close();
            return $allSets;    
        }

        /*ESTA FUNCIÓN PERMITE REALIZAR UN CONTEO DE LA CANTIDAD DIARIA DE CITAS*/
        public function reporteDiario_Grafico($fe, $idDepto){
            $sql = "SELECT count(registroservicio.FechaHora) as Cantidad from registroservicio left join serviciotramite on 
					registroservicio.idTramite = serviciotramite.idTramite
                    where date(FechaHora) = ? and idDepto = ?;";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                error_log("Connection_BD->reportePorDia_DirMed prepare error: " . $this->conn->error);
                return false;
            }

            // Forzar tipos
            $idD = (int)$idDepto;
            // Bind seguro
            if (!$stmt->bind_param("si", $fe, $idD)) {
                error_log("Connection_BD->reportePorDia_DirMed bind_param error: " . $stmt->error);
                $stmt->close();
                return false;
            }
            $stmt->bind_param("si", $fe, $idD);
            if (!$stmt->execute()) {
                // Manejo de error si falla la ejecución
                throw new Exception("Error en execute(): " . $statement->error);
            }
            //return $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $count = isset($row['Cantidad']) ? (int)$row['Cantidad'] : 0;
            return $count; //Regresa el resultado de la consulta
        }

        public function reportePorDia_DirMed($fe, $idDepto){
            $sql = "SELECT
                a.Matricula,
                a.Nombre,
                a.ApePat,
                a.ApeMat,
                a.FechaNac,
                a.FeIngreso,
                a.Correo,
                a.Telefono,
                a.Ciudad,
                a.Estado,
                a.Genero,
                im.TipoSangre,
                im.Alergias,
                im.contacto_Emergencia,
                c.idCarrera,
                rs.folioRegistro AS IdRegistro,
                rs.descripcion AS DescripcionServ,
                rs.EstatusT AS EstatusServ,
                rs.FolioSeguimiento,
                rs.FechaHora AS FechaHoraServ,
                st.idTramite AS idTramite,
                st.idDepto AS idDepto
                FROM alumno a
                LEFT JOIN carrera c ON a.idCarrera = c.idCarrera
                LEFT JOIN informacionmedica im ON a.Matricula = im.Matricula
                LEFT JOIN registroservicio rs ON a.Matricula = rs.Matricula
                LEFT JOIN serviciotramite st ON st.idTramite = rs.idTramite
                WHERE
                    (date(rs.FechaHora) = ?)
                AND
                    (st.idDepto = ?)
                ORDER BY rs.FechaHora DESC;";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                error_log("Connection_BD->reportePorDia_DirMed prepare error: " . $this->conn->error);
                return false;
            }

            // Forzar tipos
            $idD = (int)$idDepto;
            // Bind seguro
            if (!$stmt->bind_param("si", $fe, $idD)) {
                error_log("Connection_BD->reportePorDia_DirMed bind_param error: " . $stmt->error);
                $stmt->close();
                return false;
            }
            $stmt->bind_param("si", $fe, $idD);
            if (!$stmt->execute()) {
                // Manejo de error si falla la ejecución
                throw new Exception("Error en execute(): " . $statement->error);
            }
            $result = $stmt->get_result();
            return $result; //Regresa el resultado de la consulta
        }
        //idDepto = 7; Vinculación
        public function reporteGeneral_Vinc($fe1,$fe2, $idDepto){
            $sql_statement = "SELECT *FROM registroservicio rs inner join serviciotramite st on rs.idTramite = st.idTramite where date(FechaHora) BETWEEN ? AND ? and idDepto = ?";
            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                error_log("Connection_BD->reportePorDia_DirMed prepare error: " . $this->conn->error);
                return false;
            }

            // Forzar tipos
            $idD = (int)$idDepto;
            // Bind seguro
            if (!$stmt->bind_param("ssi", $fe1,$fe2, $idD)) {
                error_log("Connection_BD->reportePorDia_DirMed bind_param error: " . $stmt->error);
                $stmt->close();
                return false;
            }
            $stmt->bind_param("ssi", $fe1,$fe2, $idD);
            if (!$stmt->execute()) {
                // Manejo de error si falla la ejecución
                throw new Exception("Error en execute(): " . $statement->error);
            }
            
            // Obtener resultado (requiere mysqlnd)
            $result = $stmt->get_result();
            if ($result === false || $result === null) {
                // No hay result set o get_result no está disponible
                $stmt->close();
                return false;
            }
            return $result;
        }

        public function reporteGeneral_Vinc_Datos(){
            $sql_statement = "SELECT st.descripcion AS DescripcionT, COUNT(*) AS Total
                FROM registroservicio rs
                INNER JOIN serviciotramite st ON rs.idTramite = st.idTramite
                WHERE st.idDepto = 7
                GROUP BY st.descripcion;
            ";
            $result = $this -> conn -> query($sql_statement);
            $data = [];
            while ($row = $result -> fetch_assoc()){
                $data[] = [$row['DescripcionT'], (int) $row['Total']];
            }
            return $data;
        }
    }
?>