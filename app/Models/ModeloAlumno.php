<?php
    //Creación de la clase del modelo de Alumnos
    class AlumnoModel{
        private $conn;
        //Construcción del contructor del modelo
        public function __construct($conn){
            $this->conn = $conn;
        }
        public function insertarAlumno(Alumno $unAlumno){
            //Lógica para las acciones dentro de la base de datos
            //Codigo para hacer el registro de alumnos
            $sql_statement = "INSERT INTO Alumno (Matricula, Nombre, ApePat, ApeMat, FechaNac, FeIngreso, Correo, Direccion, Telefono, Ciudad, Estado, Genero, idCarrera)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; // Aquí iría la llamada al procedimiento almacenado para registrar alumno

            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                die("Connection_BD->registrarAlumno prepare error: " . $this->conn->error);
            }

            //Obtención de los datos del Alumno a variables
            $matricula = $unAlumno->getMatricula();
            $nombre = $unAlumno->getNombre();
            $ApePat = $unAlumno->getApePat();
            $ApeMat = $unAlumno->getApeMat();
            $FechaNac = $unAlumno->getFechaNac();
            $FeIngreso = $unAlumno->getFeIngreso();
            $correo = $unAlumno->getCorreo();
            $direccion = $unAlumno->getDireccion();
            $telefono = $unAlumno->getTelefono();
            $ciudad = $unAlumno->getCiudad();
            $estado = $unAlumno->getEstado();
            $genero = $unAlumno->getGenero();
            $idCarrera = (int)($unAlumno->getIdCarrera());

            // Ahora sí, bind_param con las variables locales:
            $stmt->bind_param("ssssssssssssi",
                $matricula,
                $nombre,
                $ApePat,
                $ApeMat,
                $FechaNac,
                $FeIngreso,
                $correo,
                $direccion,
                $telefono,
                $ciudad,
                $estado,
                $genero,
                $idCarrera
            ); //Recibe argumentos
            return $stmt -> execute();
            /*
            if (!$stmt->execute()) {
                echo("Connection_BD->registrarAlumno execute error: " . $stmt->error);
                return false;
            }
            $stmt->close();

            return true;
            */
        }

        public function insertarInfoMedica(InformacionMedica $unaInforMed) {
            /*Realizamos la recuperación de los objetos para mandar la información*/
            $matriculaID = $unaInforMed->getMatricula();
            $tipoSangre = $unaInforMed->getTipoSangre();
            $alergias = $unaInforMed->getAlergias();
            $numContacto = $unaInforMed->getContactoEmergencia();

            /*Hacemos la variable que contendra el query sql*/
            $sql_statement = "INSERT INTO InformacionMedica(idInfoM,Matricula,TipoSangre,Alergias,contacto_emergencia) values (NULL,?,?,?,?)";

            /*Mandamos los parametros*/
            $statement = $this->conn->prepare($sql_statement);
            if (!$statement) {
                die("Connection_BD->insertarInfoMedica prepare error: " . $this->conn->error);
            }
            $statement -> bind_param('ssss', $matriculaID, $tipoSangre, $alergias, $numContacto);
            /*Retornamos el statement*/
            return $statement -> execute();
        }
        public function asignarHashQR(Alumno $alumno): bool {
            /*Hacemos la variable que contendra el query sql*/
            $matricula = $alumno->getMatricula();
            $hash = $alumno->getQrHash();
            $sql_statement = "update Alumno set qrHash = ? where Matricula = ?";
            $statement = $this->conn->prepare($sql_statement);

            $statement -> bind_param('ss',$hash, $matricula);
            if (!$statement) {
                die("Connection_BD->asignarHashQR prepare error: " . $this->conn->error);
            }
            return $statement -> execute();
        }

        public function actualizarAlumno(Alumno $unAlumno, string $matriculaOriginal): bool {
            // Código para actualizar los datos del alumno
            // Nota: Matricula y FeIngreso no se pueden modificar según las reglas de negocio
            $sql_statement = "UPDATE Alumno SET
                Nombre = ?,
                ApePat = ?,
                ApeMat = ?,
                FechaNac = ?,
                Correo = ?,
                Direccion = ?,
                Telefono = ?,
                Ciudad = ?,
                Estado = ?,
                Genero = ?,
                idCarrera = ?
                WHERE Matricula = ?";
            
            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                die("Connection_BD->actualizarAlumno prepare error: " . $this->conn->error);
            }

            // Obtención de los datos del Alumno a variables
            $nombre = $unAlumno->getNombre();
            $ApePat = $unAlumno->getApePat();
            $ApeMat = $unAlumno->getApeMat();
            $FechaNac = $unAlumno->getFechaNac();
            $correo = $unAlumno->getCorreo();
            $direccion = $unAlumno->getDireccion();
            $telefono = $unAlumno->getTelefono();
            $ciudad = $unAlumno->getCiudad();
            $estado = $unAlumno->getEstado();
            $genero = $unAlumno->getGenero();
            $idCarrera = (int)($unAlumno->getIdCarrera());

            // Bind parameters (sin matricula y feIngreso)
            $stmt->bind_param("ssssssssssis",
                $nombre,
                $ApePat,
                $ApeMat,
                $FechaNac,
                $correo,
                $direccion,
                $telefono,
                $ciudad,
                $estado,
                $genero,
                $idCarrera,
                $matriculaOriginal
            );

            return $stmt->execute();
        }

        public function actualizarInfoMedica(InformacionMedica $unaInforMed): bool {
            /* Código para actualizar la información médica */
            $matriculaID = $unaInforMed->getMatricula();
            $tipoSangre = $unaInforMed->getTipoSangre();
            $alergias = $unaInforMed->getAlergias();
            $numContacto = $unaInforMed->getContactoEmergencia();

            /* Query SQL para actualizar */
            $sql_statement = "UPDATE InformacionMedica SET
                TipoSangre = ?,
                Alergias = ?,
                contacto_emergencia = ?
                WHERE Matricula = ?";

            /* Mandamos los parámetros */
            $statement = $this->conn->prepare($sql_statement);
            if (!$statement) {
                die("Connection_BD->actualizarInfoMedica prepare error: " . $this->conn->error);
            }
            $statement->bind_param('ssss', $tipoSangre, $alergias, $numContacto, $matriculaID);

            /* Retornamos el resultado */
            return $statement->execute();
        }

        public function obtenerAlumnoPorMatricula(string $matricula): ?array {
            $sql_statement = "SELECT Alumno.*, InformacionMedica.* FROM Alumno
                LEFT JOIN InformacionMedica ON Alumno.Matricula = InformacionMedica.Matricula
                WHERE Alumno.Matricula = ?";

            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                die("Connection_BD->obtenerAlumnoPorMatricula prepare error: " . $this->conn->error);
            }

            $stmt->bind_param("s", $matricula);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            return null;
        }

        public function recuperarDatosAlumnoPorMatricula(string $matricula){
            $sql_statement = "
                SELECT 
                    a.*, 
                    c.*, 
                    im.*, 
                    calcCuatrimestre(a.FeIngreso) AS Cuatri,
                    calcPeriodo(a.FeIngreso) AS Periodo
                FROM alumno a
                LEFT JOIN carrera c ON a.idCarrera = c.idCarrera
                LEFT JOIN informacionmedica im ON a.Matricula = im.Matricula
                WHERE a.Matricula = ?;
            ";

            $statement = $this->conn->prepare($sql_statement);
            if (!$statement) {
                die("Connection_BD->recuperarDatosAlumnoPorMatricula prepare error: " . $this->conn->error);
            }

            $statement->bind_param("s", $matricula);
            $statement->execute();
            $result = $statement->get_result();

            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            //Si no encuentra registros, retorna NULL
            return null;
        }

        public function obtenerTodosAlumnos(){
            $sql_statement = "SELECT a.Matricula, a.Nombre, a.ApePat, a.ApeMat,a.FeIngreso,a.idCarrera, calcCuatrimestre(a.FeIngreso) as Cuatrimestre, a.FechaNac, a.Correo, im.TipoSangre FROM Alumno a LEFT JOIN InformacionMedica im ON a.Matricula = im.Matricula";
            
            $result = $this->conn->query($sql_statement);
            return $result;
        }

        public function eliminarAlumno($matricula){
            //Codigo para hacer la baja del Alumno
            $sql_statement = "call darBajaAlumno(?, @eliminado)";
            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                echo("Connection_BD->darBajaAlumno prepare error: " . $this->conn->errno);
                return false;
            }

            $stmt->bind_param("s", $matricula);
            if (!$stmt->execute()) {
                echo("Connection_BD->darBajaAlumno execute error: " . $stmt->error);
                return false;
            }
            $stmt->close();

            $res = $this->conn->query("SELECT @eliminado AS eliminado");
            if ($res && $row = $res->fetch_assoc()) {
                return (int)$row['eliminado'];
                $stmt->close();
            }
            return 0;
        }

        /*METODO/FUNCIÓN PARA ASIGNAR EL NUEVO HASH DEL ALUMNO */
    }
