# +===================================================================
# |
# | Generado el 22-11-2025 a las 05:06:01 PM
# | Servidor: localhost
# | MySQL Version: 10.4.32-MariaDB
# | PHP Version: 8.2.12
# | Base de datos: 'identiqr'
# | Tablas: alumno;  carrera;  departamento;  historialinfoqr;  informacionmedica;  registroservicio;  serviciotramite;  usuario
# |
# +-------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS=0;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `alumno`;
CREATE TABLE `alumno` (
  `Matricula` varchar(15) NOT NULL,
  `Nombre` varchar(60) NOT NULL,
  `ApePat` varchar(60) NOT NULL,
  `ApeMat` varchar(60) DEFAULT NULL,
  `FechaNac` date NOT NULL,
  `FeIngreso` date NOT NULL,
  `Correo` varchar(60) DEFAULT NULL,
  `Direccion` varchar(60) NOT NULL,
  `Telefono` varchar(15) NOT NULL,
  `Ciudad` varchar(45) NOT NULL,
  `Estado` varchar(45) DEFAULT NULL,
  `Genero` varchar(10) DEFAULT 'Otro',
  `idCarrera` int(11) NOT NULL,
  `qrHash` text DEFAULT 'Pendiente',
  `fechaGenerarQR_1` date NOT NULL,
  `fechaExpiracionQR_2` date DEFAULT NULL,
  PRIMARY KEY (`Matricula`),
  UNIQUE KEY `Correo` (`Correo`),
  KEY `idCarrera` (`idCarrera`),
  CONSTRAINT `alumno_ibfk_1` FOREIGN KEY (`idCarrera`) REFERENCES `carrera` (`idCarrera`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `alumno` VALUES ('1', '1', '1', '1', '2005-10-27', '2023-09-01', '1@upemor.edu.mx', '1234', '111-111-2222', '123', '123', 'Femenino', '2', 'f72c32c1e67eb82a642c6b7b149fc2c6d7a6ed02d3795f1ce5b26008b1ffb894', '2025-11-22', '2026-03-22');
INSERT INTO `alumno` VALUES ('2', '2', '2', '2', '2005-01-27', '2024-09-01', '2@upemor.edu.mx', '222', '222-555-6666', '2323', '8585', 'Otro', '3', 'c682dc65f38674d9cc5c1dea208b500d375bd8217eb38c0211bc04819dff312d', '2025-11-22', '2026-03-22');
INSERT INTO `alumno` VALUES ('3', '3', '3', '3', '2008-05-08', '2024-09-01', '3@upemor.edu.mx', '23', '333-888-9999', '3', '3', 'PrefieroNo', '4', '16feb5c7e5bc3b258aa2b22932721563fd268e91b2f1713ce88acf5d9bcbc3bc', '2025-11-22', '2026-03-22');
INSERT INTO `alumno` VALUES ('PRUEBA1', 'PRUEBA', '1', '1', '2005-10-27', '2023-09-01', 'PRUEBA1@upemor.edu.mx', 'Jose', '777-159-6969', 'Mor', 'Morelos', 'Masculino', '1', '40abdddaa66b5872f02dc955da1ff12a57076f9057b78c461b5eed741d43c06c', '2025-11-22', '2026-03-22');
INSERT INTO `alumno` VALUES ('SLAO230035', 'ALEXIS SEBASTIAN', 'AAA', 'SANCHEZ LUNA', '2005-10-27', '2023-09-01', 'SLAO230035@upemor.edu.mx', 'Calle Santa Maria Lt 11, Col El Porvenir, Jiutepec Mor.', '777-480-5924', 'Jiutepec', 'Mor.', 'Masculino', '1', 'cf50c9ffd9afad172c208d6ea2bdad23a560d097f57a78a3f6267ec346e99b18', '2025-11-19', '2026-03-19');
INSERT INTO `alumno` VALUES ('SLAO230036', 'ALEXIS SEBASTIAN', 'Sanchez', 'SANCHEZ LUNA', '2025-10-27', '2023-09-01', 'alexissanchezluna@outlook.com', 'Calle Santa Maria Lt 11, Col El Porvenir, Jiutepec Mor.', '777-480-5924', 'Jiutepec', 'Mor.', 'Masculino', '1', 'f61affe332753973ea50003a7da589f52240e0549741ef0d6037c8060f972b58', '2025-11-19', '2026-03-19');

DROP TABLE IF EXISTS `carrera`;
CREATE TABLE `carrera` (
  `idCarrera` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `planEstudios` varchar(45) NOT NULL,
  `idDepto` int(3) NOT NULL,
  PRIMARY KEY (`idCarrera`),
  KEY `idDepto` (`idDepto`),
  CONSTRAINT `carrera_ibfk_1` FOREIGN KEY (`idDepto`) REFERENCES `departamento` (`idDepto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `carrera` VALUES ('1', 'ITI', 'ITI-H18', '2');
INSERT INTO `carrera` VALUES ('2', 'IID', 'TSU-DS-NM24', '2');
INSERT INTO `carrera` VALUES ('3', 'IET', 'IET-H18', '2');
INSERT INTO `carrera` VALUES ('4', 'ISE', 'TSU-RC-NM24', '2');

DROP TABLE IF EXISTS `departamento`;
CREATE TABLE `departamento` (
  `idDepto` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(60) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`idDepto`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `departamento` VALUES ('1', 'Sin Dirección asociada', 'Sin dirección.');
INSERT INTO `departamento` VALUES ('2', 'Direccion Academica', 'Dirección de carrera');
INSERT INTO `departamento` VALUES ('3', 'Servicios Escolares', 'Administración de trámites académicos y servicios para los estudiantes');
INSERT INTO `departamento` VALUES ('4', 'Dirección Desarrollo Academico', 'Gestión y mejora de los procesos académicos y pedagógicos (Tutoria)');
INSERT INTO `departamento` VALUES ('5', 'Dirección Asuntos Estudiantiles', 'Coordinación de actividades y servicios para el bienestar estudiantil');
INSERT INTO `departamento` VALUES ('6', 'Consultorio de atención de primer contacto', 'Consultoría y atención médica inicial para estudiantes');
INSERT INTO `departamento` VALUES ('7', 'Vinculación', 'Conexión con empresas, organizaciones y otras instituciones educativas');

DROP TABLE IF EXISTS `historialinfoqr`;
CREATE TABLE `historialinfoqr` (
  `idHistorialInfoQR` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `fechaHora` datetime NOT NULL,
  `accionRealizada` text DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `usuarioRealizado` varchar(45) NOT NULL,
  PRIMARY KEY (`idHistorialInfoQR`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `historialinfoqr` VALUES ('00001', '2025-11-19 18:55:44', 'Registro', 'Usuario nuevo dado de alta. Identi identiqr.info@gmail.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00002', '2025-11-19 18:55:44', 'Registro', 'Usuario nuevo dado de alta. Identi identiQR.info_Dir@identiqr.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00003', '2025-11-19 18:55:44', 'Registro', 'Usuario nuevo dado de alta. Identi identiQR.info_ServEsco@identiqr.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00004', '2025-11-19 18:55:44', 'Registro', 'Usuario nuevo dado de alta. Identi identiQR.info_DDA@identiqr.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00005', '2025-11-19 18:55:44', 'Registro', 'Usuario nuevo dado de alta. Identi identiQR.info_DAE@identiqr.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00006', '2025-11-19 18:55:44', 'Registro', 'Usuario nuevo dado de alta. Identi identiQR.info_Med@identiqr.com', 'Realizado por:  root@localhost');

DROP TABLE IF EXISTS `informacionmedica`;
CREATE TABLE `informacionmedica` (
  `idInfoM` int(11) NOT NULL AUTO_INCREMENT,
  `Matricula` varchar(15) NOT NULL,
  `TipoSangre` varchar(5) NOT NULL,
  `Alergias` text DEFAULT 'Sin_Alergias',
  `contacto_emergencia` varchar(15) DEFAULT NULL,
  `fechaIngreso_InfoMed` date DEFAULT NULL,
  PRIMARY KEY (`idInfoM`),
  KEY `Matricula` (`Matricula`),
  CONSTRAINT `informacionmedica_ibfk_1` FOREIGN KEY (`Matricula`) REFERENCES `alumno` (`Matricula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `informacionmedica` VALUES ('1', 'SLAO230036', 'A+', 'S/A', '777-123-4567', '2025-11-19');
INSERT INTO `informacionmedica` VALUES ('2', 'SLAO230035', 'A+', '1/A', '777-123-4567', '2025-11-19');
INSERT INTO `informacionmedica` VALUES ('3', 'PRUEBA1', 'A+', 'S/A', '777-458-5858', '2025-11-22');
INSERT INTO `informacionmedica` VALUES ('4', '1', 'A+', '1', '111-253-6969', '2025-11-22');
INSERT INTO `informacionmedica` VALUES ('5', '2', 'A+', '25', '123-456-7898', '2025-11-22');
INSERT INTO `informacionmedica` VALUES ('6', '3', 'A+', '25', '777-159-6969', '2025-11-22');

DROP TABLE IF EXISTS `registroservicio`;
CREATE TABLE `registroservicio` (
  `FolioRegistro` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `Matricula` varchar(15) NOT NULL,
  `idTramite` int(4) unsigned zerofill NOT NULL,
  `FechaHora` datetime NOT NULL,
  `descripcion` text DEFAULT 'S/D',
  `estatusT` varchar(45) DEFAULT 'Pendiente',
  `FolioSeguimiento` varchar(45) DEFAULT 'Procesando',
  PRIMARY KEY (`FolioRegistro`),
  KEY `Matricula` (`Matricula`),
  KEY `idTramite` (`idTramite`),
  CONSTRAINT `registroservicio_ibfk_1` FOREIGN KEY (`Matricula`) REFERENCES `alumno` (`Matricula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `registroservicio_ibfk_2` FOREIGN KEY (`idTramite`) REFERENCES `serviciotramite` (`idTramite`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `registroservicio` VALUES ('0001', 'SLAO230036', '0001', '2025-11-19 21:02:48', 'El Alumno [ALEXIS SEBASTIAN Sanchez SANCHEZ LUNA] con matrícula [SLAO230036] del cuatrimestre [7] de la carrera [ITI] <Solicitó unirse al extracurricular> de [Extracurricular-Cine debate]. Datos Médicos [$S/A- Sangre: A+]', 'Pendiente', 'SLAO230036-202519Ext-FPLM');
INSERT INTO `registroservicio` VALUES ('0002', 'SLAO230036', '0001', '2025-11-19 21:27:10', 'El Alumno [ALEXIS SEBASTIAN Sanchez SANCHEZ LUNA] con matrícula [SLAO230036] del cuatrimestre [7] de la carrera [ITI] <Solicitó unirse al extracurricular> de [Extracurricular-Pasaporte literario]. Datos Médicos [$S/A- Sangre: A+]', 'Pendiente', 'SLAO230036-202519Ext-UEOH');
INSERT INTO `registroservicio` VALUES ('0003', 'SLAO230036', '0002', '2025-11-19 21:41:41', 'El Alumno [ALEXIS SEBASTIAN Sanchez SANCHEZ LUNA] con matrícula [SLAO230036] del cuatrimestre [7] con el Plan de estudio <ITI-H18> durante el periodo [7O-2025] asistió [Asitio a tutorias GRUPALES: -  10 veces] y [Tutorias - Individuales: 1 veces] con el tutor/a: <Dr. Juan Paulo Sánchez Hernández>', 'Pendiente', 'SLAO230036-202519Tut-YDTJ');
INSERT INTO `registroservicio` VALUES ('0004', 'SLAO230036', '0011', '2025-11-19 22:12:46', 'El Alumno [ALEXIS SEBASTIAN Sanchez SANCHEZ LUNA] con matrícula [SLAO230036] del cuatrimestre [7] de la carrera [ITI] solicitó un <JUSTIFICANTE> para el día [2025-11-19]. Requisitos o notas [Motivo de salud]', 'Pendiente', 'SLAO230036-202519Jus-RKBB');
INSERT INTO `registroservicio` VALUES ('0005', 'SLAO230036', '0004', '2025-11-19 22:26:31', 'El Alumno [ALEXIS SEBASTIAN Sanchez SANCHEZ LUNA] con matrícula [SLAO230036] y correo [alexissanchezluna@outlook.com], inscrito en el 7 Cuatri de [ITI], <7O-2025> |PERIODO: <Solicitó el tramite> [Tramite de Servicios escolares - 3] el día <2025-11-19> |Motivo adicional: [TITULACION] | Monto pagado: [$150.00] | Método de pago: [TDD]. Requerimientos extras: <10 cuatri>', 'Pendiente', 'SLAO230036-202519Ins-RQHM');
INSERT INTO `registroservicio` VALUES ('0006', 'SLAO230036', '0005', '2025-11-19 23:16:06', 'El alumno [ALEXIS SEBASTIAN Sanchez SANCHEZ LUNA] [Matrícula: SLAO230036], de <7°> Cuatri en la carrera <ITI>, solicitó el trámite de [Estancia I - Solicitó iniciar su primera estancia profesional - Fecha: <2025-11-19>]. Documentos entregados: [INE, Comprobante]. Documentos finales correctos y adecuados: [Si].', 'Pendiente', 'SLAO230036-202519Est-JXMP');
INSERT INTO `registroservicio` VALUES ('0007', 'SLAO230036', '0006', '2025-11-19 23:16:36', 'El alumno [ALEXIS SEBASTIAN Sanchez SANCHEZ LUNA] [Matrícula: SLAO230036], de <7°> Cuatri en la carrera <ITI>, solicitó el trámite de [Estancia II - Solicitó continuar con su segunda estancia profesional - Fecha: <2025-11-19>]. Documentos entregados: [INE]. Documentos finales correctos y adecuados: [Si].', 'Pendiente', 'SLAO230036-202519Est-FVOH');
INSERT INTO `registroservicio` VALUES ('0008', 'SLAO230036', '0007', '2025-11-19 23:16:51', 'El alumno [ALEXIS SEBASTIAN Sanchez SANCHEZ LUNA] [Matrícula: SLAO230036], de <7°> Cuatri en la carrera <ITI>, solicitó el trámite de [Estadía - Solicitó registrar su estadía profesional - Fecha: <2025-11-19>]. Documentos entregados: [Comprobante]. Documentos finales correctos y adecuados: [No].', 'Pendiente', 'SLAO230036-202519Est-GKHH');
INSERT INTO `registroservicio` VALUES ('0009', 'SLAO230036', '0008', '2025-11-19 23:17:13', 'El alumno [ALEXIS SEBASTIAN Sanchez SANCHEZ LUNA] [Matrícula: SLAO230036], de <7°> Cuatri en la carrera <ITI>, solicitó el trámite de [Prácticas profesionales - Solicitó realizar sus prácticas profesionales - Fecha: <2025-11-19>]. Documentos entregados: [CartaMotivos]. Documentos finales correctos y adecuados: [Si].', 'Pendiente', 'SLAO230036-202519Ser-WFKN');
INSERT INTO `registroservicio` VALUES ('0010', 'SLAO230036', '0009', '2025-11-19 23:17:30', 'El alumno [ALEXIS SEBASTIAN Sanchez SANCHEZ LUNA] [Matrícula: SLAO230036], de <7°> Cuatri en la carrera <ITI>, solicitó el trámite de [Servicio social - Solicitó iniciar su servicio social - Fecha: <2025-11-19>]. Documentos entregados: [CartaMotivos, CartaAceptacion, PlanActividades]. Documentos finales correctos y adecuados: [Si].', 'Pendiente', 'SLAO230036-202519Pra-UIFB');

DROP TABLE IF EXISTS `serviciotramite`;
CREATE TABLE `serviciotramite` (
  `idTramite` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(45) NOT NULL,
  `idDepto` int(11) DEFAULT NULL,
  PRIMARY KEY (`idTramite`),
  KEY `idDepto` (`idDepto`),
  CONSTRAINT `serviciotramite_ibfk_1` FOREIGN KEY (`idDepto`) REFERENCES `departamento` (`idDepto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `serviciotramite` VALUES ('0001', 'Extracurricular', '5');
INSERT INTO `serviciotramite` VALUES ('0002', 'Tutorias', '4');
INSERT INTO `serviciotramite` VALUES ('0003', 'Reinscripcion', '3');
INSERT INTO `serviciotramite` VALUES ('0004', 'Inscripcion', '3');
INSERT INTO `serviciotramite` VALUES ('0005', 'Estancia I', '7');
INSERT INTO `serviciotramite` VALUES ('0006', 'Estancia II', '7');
INSERT INTO `serviciotramite` VALUES ('0007', 'Estadia', '7');
INSERT INTO `serviciotramite` VALUES ('0008', 'Servicio Social', '7');
INSERT INTO `serviciotramite` VALUES ('0009', 'Practicas Profesionales', '7');
INSERT INTO `serviciotramite` VALUES ('0010', 'Reposicion Credencial', '3');
INSERT INTO `serviciotramite` VALUES ('0011', 'Justificante', '2');
INSERT INTO `serviciotramite` VALUES ('0012', 'Recursamiento', '2');
INSERT INTO `serviciotramite` VALUES ('0013', 'Consulta medica', '6');
INSERT INTO `serviciotramite` VALUES ('0014', 'Constancias e historial', '3');

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `apellido_paterno` varchar(50) NOT NULL,
  `apellido_materno` varchar(50) NOT NULL,
  `genero` varchar(45) DEFAULT 'Otro',
  `usr` varchar(45) NOT NULL,
  `email` varchar(60) NOT NULL,
  `passw` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `idDepto` int(11) DEFAULT NULL,
  `FechaRegistro` datetime NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  KEY `idDepto` (`idDepto`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`idDepto`) REFERENCES `departamento` (`idDepto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuario` VALUES ('1', 'Identi', 'Q', 'R', 'Otro', 'IQR2025-A5', 'identiqr.info@gmail.com', '16810c2c3c47c48bac34b13477334246', 'Administrador', '1', '2025-11-19 18:55:44');
INSERT INTO `usuario` VALUES ('2', 'Identi', 'Q', 'R_Dir', 'Otro', 'IQR2025-A5', 'identiQR.info_Dir@identiqr.com', '05bd8f267c49f809ab073d3be2523acb', 'Administrativo_Direccion', '2', '2025-11-19 18:55:44');
INSERT INTO `usuario` VALUES ('3', 'Identi', 'Q', 'R_ServEsco', 'Otro', 'IQO2025-A5', 'identiQR.info_ServEsco@identiqr.com', '485c23b3fb485647370b53f212bee674', 'Administrativo_ServicioEsco', '3', '2025-11-19 18:55:44');
INSERT INTO `usuario` VALUES ('4', 'Identi', 'Q', 'R_DDA', 'Otro', 'IQA2025-A5', 'identiQR.info_DDA@identiqr.com', '4195d5c2016569adde5a618cb2a70d0f', 'Administrativo_DesaAca', '4', '2025-11-19 18:55:44');
INSERT INTO `usuario` VALUES ('5', 'Identi', 'Q', 'R_DAE', 'Otro', 'IQE2025-A5', 'identiQR.info_DAE@identiqr.com', 'a0820dc6fe69a2d2896c2ccaf833c40d', 'Administrativo_DAE', '5', '2025-11-19 18:55:44');
INSERT INTO `usuario` VALUES ('6', 'Identi', 'Q', 'R_Med', 'Otro', 'IQD2025-A5', 'identiQR.info_Med@identiqr.com', '0a7ca5f74a62338919049db9b8e67c58', 'Administrativo_Medico', '6', '2025-11-19 18:55:44');

DROP FUNCTION IF EXISTS `calcCuatrimestre`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `calcCuatrimestre`(feIngreso date) RETURNS int(11)
    DETERMINISTIC
begin
	declare m, c int; #Meses || Cuatrimestres
    set m = timestampdiff(month,feIngreso,curdate());
    set c = (truncate(m/4,0) + 1);
    
    return c;
end//
DELIMITER ;

DROP FUNCTION IF EXISTS `calcPeriodo`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `calcPeriodo`(feIngreso DATE) RETURNS varchar(10) CHARSET utf8mb4 COLLATE utf8mb4_general_ci
    DETERMINISTIC
BEGIN
    DECLARE cuatri INT;
    DECLARE periodo CHAR(1);
    DECLARE anio CHAR(4);

    #1. Obtener el cuatrimestre actual con la función interna
    SET cuatri = CalcCuatrimestre(feIngreso);

    #2. Determinar el periodo según el mes actual
    CASE 
        WHEN MONTH(CURDATE()) BETWEEN 1 AND 4 THEN 
            SET periodo = 'I';   -- Invierno
        WHEN MONTH(CURDATE()) BETWEEN 5 AND 8 THEN 
            SET periodo = 'P';   -- Primavera
        WHEN MONTH(CURDATE()) BETWEEN 9 AND 12 THEN 
            SET periodo = 'O';   -- Otoño
    END CASE;

    #3. Obtener el año actual
    SET anio = YEAR(CURDATE());

    -- 4. Retornar en formato "7O-2025"
    RETURN CONCAT(cuatri, periodo, '-', anio);
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS `cancelarTramite`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `cancelarTramite`(IN FR int, OUT eliminado INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET eliminado = 0;
    END;

    START TRANSACTION;
    DELETE FROM registroservicio WHERE (FolioRegistro = FR);
    SET eliminado = ROW_COUNT(); -- número de filas afectadas
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS `cancelarTramite2`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `cancelarTramite2`(IN FS text, OUT eliminado INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET eliminado = 0;
    END;

    START TRANSACTION;
    DELETE FROM registroservicio WHERE (FolioSeguimiento = FS);
    SET eliminado = ROW_COUNT(); -- número de filas afectadas
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS `darBajaAlumno`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `darBajaAlumno`(IN matr VARCHAR(45), OUT eliminado INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET eliminado = 0;
    END;

    START TRANSACTION;
    DELETE FROM alumno WHERE (Matricula = matr);
    SET eliminado = ROW_COUNT(); -- número de filas afectadas
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS `darBajaUsuario`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `darBajaUsuario`(IN usuario VARCHAR(45), OUT eliminado INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET eliminado = 0;
    END;

    START TRANSACTION;
    DELETE FROM usuario WHERE (usr = usuario);
    SET eliminado = ROW_COUNT(); -- número de filas afectadas
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS `Login`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `Login`(in us varchar(255), in pass text)
begin
	declare pass_md5 text;
    
    set pass_md5 = md5(pass);
    
    select * from usuario where (usr = us or email = us) and passw = pass_md5; 
end//
DELIMITER ;

DROP PROCEDURE IF EXISTS `registrarUsuariosSP`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarUsuariosSP`(in nombre varchar(60), in ApP varchar(45), in ApM varchar(45),in usr varchar(100), in email varchar(100), in passw varchar(255), in rol varchar(45), in idDepto int)
begin
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Si ocurre un error, se revierte la transacción, NO INGRESA
        ROLLBACK;
    END;

    START TRANSACTION; #Empezamos la transacción
    INSERT INTO Usuario (nombre,apellido_paterno,apellido_materno,usr,email,passw,rol,idDepto) 
		VALUES (nombre,ApP,ApM,usr,email,passw,rol,idDepto);
        
	SELECT LAST_INSERT_ID() AS id_usuario;
    COMMIT;
end//
DELIMITER ;

DROP PROCEDURE IF EXISTS `reporteInd_DirMed`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `reporteInd_DirMed`(in opc int,in f1 date,in f2 date, in g varchar(15), in idD int)
begin

	#Declaramos los atributos en donde se va a almacenar la información
    declare idDe int default 6; #Este sera una variable para modificar que departamento se refiere
    declare Matri,Nom,Pat,Mat,Corr,Tel,Ciu,Est,Gen,TipSan,Aler,Per,ContEme varchar(60); #Matricula, Nombre, Paterno, Materno, Correo, Telefono,Ciudad,Estado,Genero,TipoSangre,Alergias,Periodo,Contacto_emergencia
    declare Des,plEst varchar(45); #descripción, planEstudios,
    declare idCar, idDep, idInf, FolReg, idTra int; #idCarrera,idDepto,idInfoM, idTramite, idDepto
    declare DesServ, EstT, FolSeg text; #Descripción(registroservicio), estatusT, FolioSeguimiento
    declare FeNac,FeIng date; #FechaNacimiento, FechaIngreso
    declare FeHor datetime; #FechaHora
    declare buscarRep_DirMed cursor for
		SELECT
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
            (opc = 1 AND rs.FechaHora BETWEEN f1 AND f2)
		OR
            (opc = 2 AND a.Genero = g)
		AND
			(idD = idDe)
        ORDER BY rs.FechaHora DESC;
	declare continue handler for not found set @x = true;
    open buscarRep_DirMed;
    
     -- Validación simple de parámetro
    IF opc NOT IN (1,2) THEN
        SIGNAL SQLSTATE '22003' 
            SET MESSAGE_TEXT = 'Opción no válida. Usa 1 = rango fechas o 2 = género';
    END IF;
    
    loop1:loop
		fetch buscarRep_DirMed into Matri,Nom,Pat,Mat,FeNac,FeIng,Corr,Tel,Ciu,Est,Gen,TipSan,Aler,ContEme,idCar,FolReg,DesServ,EstT,FolSeg,FeHor,idTra,idDep;
    if @x then
		leave loop1;
	end if;
		select Matri,Nom,Pat,Mat,FeNac,FeIng,Corr,Tel,Ciu,Est,Gen,TipSan,Aler,ContEme,idCar,FolReg,DesServ,EstT,FolSeg,FeHor,idTra,idDep;
	end loop;
    close buscarRep_DirMed;
end//
DELIMITER ;

DROP TRIGGER IF EXISTS `actHash_QR_Alumno`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` trigger actHash_QR_Alumno before update on alumno
for each row
begin
	if ((old.qrHash != new.qrHash) and (new.qrHash != 'Pendiente')) then
        set new.fechaGenerarQR_1 = curdate();
        set new.fechaExpiracionQR_2 = adddate(new.fechaGenerarQR_1, interval 4 month); /*En automatico la fecha del HASH o de expiración sera en 4 MESES.*/
    end if;
end//
DELIMITER ;

DROP TRIGGER IF EXISTS `ingInfoMed_Medico`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` trigger ingInfoMed_Medico before insert on informacionMedica
for each row
begin
	set new.fechaIngreso_InfoMed = curdate();
end//
DELIMITER ;

DROP TRIGGER IF EXISTS `actInfoMed_Medico`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` trigger actInfoMed_Medico before update on informacionMedica
for each row
begin
	if(old.fechaIngreso_InfoMed != new.fechaIngreso_InfoMed) then
		set new.fechaIngreso_InfoMed = curdate(); 
    end if;
end//
DELIMITER ;

DROP TRIGGER IF EXISTS `regisServ_Tramite`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` trigger regisServ_Tramite before insert on registroservicio
for each row
begin
	declare letras varchar(4);
    declare tipoTram varchar(45);
	set new.FechaHora = now();
    set tipoTram = (select Descripcion from serviciotramite where idTramite = new.idTramite);
    
	set letras = CONCAT(
        char(FLOOR(RAND() * 26) + 65),
        char(FLOOR(RAND() * 26) + 65),
        char(FLOOR(RAND() * 26) + 65),
        char(FLOOR(RAND() * 26) + 65)
    );
    
    set new.FolioSeguimiento = concat(new.matricula,"-",year(curdate()),day(curdate()),substring(tipoTram,1,3),"-",letras);
end//
DELIMITER ;

DROP TRIGGER IF EXISTS `IngresoUsuario`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` trigger IngresoUsuario before insert on Usuario
for each row
begin
	declare n,p,m varchar(10);
    set new.passw = MD5(new.passw);
    set new.FechaRegistro = now();
    set new.usr = upper(concat(left(new.nombre,1),left(new.apellido_paterno,1),right(new.apellido_materno,1),year(new.FechaRegistro),"-",substring(UUID(),1,2)));
end//
DELIMITER ;

DROP TRIGGER IF EXISTS `Ing_Bita_Usuario`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` trigger Ing_Bita_Usuario after insert on Usuario
for each row
begin
	declare mensaje text;
    declare accionR varchar(20); #Registro,Modificación,Baja
    declare usR text;
    
    set mensaje = concat_ws(" ", "Usuario nuevo dado de alta.",new.nombre, new.email);
    set accionR = "Registro";
    set usR = concat_ws(" ", "Realizado por: ", user());
    
    
    insert into HistorialInfoQR values (0,now(),accionR,mensaje,usR);

end//
DELIMITER ;

DROP TRIGGER IF EXISTS `IngresoUsuarioAct`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` trigger IngresoUsuarioAct before update on Usuario
for each row
begin
	if(new.passw != old.passw) then
		set new.passw = MD5(new.passw);
		set new.FechaRegistro = now();
    end if;
    /*--DIFERENTES TIPOS DE ENCRIPTACIÓN
    select Password("abc");
	select MD5("abc");
	select sha("AAA");
	select aes_encrypt("AAA","clave") into @x;
	select convert(aes_decrypt(@x,"clave") using utf8);
    
    https://youtu.be/JQnrO1eDR-0?si=4VP6ykF-bHJVpSEc
    */
end//
DELIMITER ;

DROP TRIGGER IF EXISTS `Act_Bita_Usuario`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` trigger Act_Bita_Usuario after update on Usuario
for each row
begin
	declare mensaje text;
    declare accionR varchar(20); #Registro,Modificación,Baja
    declare usR text;
    
    set mensaje = concat_ws(" ", "Usuario actualizado datos: ",new.nombre, new.email, new.passw, "Anteriores: ", old.nombre,old.email,old.passw);
    set accionR = "Actualización";
    set usR = concat_ws(" ", "Realizado por: ", user());
    
    
    insert into HistorialInfoQR values (0,now(),accionR,mensaje,usR);

end//
DELIMITER ;

DROP TRIGGER IF EXISTS `Baja_Bita_Usuario`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` trigger Baja_Bita_Usuario after delete on Usuario
for each row
begin
	declare mensaje text;
    declare accionR varchar(20); #Registro,Modificación,Baja
    declare usR text;
    
    set mensaje = concat_ws(" ", "Usuario viejo dado de baja.",old.nombre, old.email);
    set accionR = "Baja";
    set usR = concat_ws(" ", "Realizado por: ", user());
    
    insert into HistorialInfoQR values (0,now(),accionR,mensaje,usR);
end//
DELIMITER ;

SET FOREIGN_KEY_CHECKS=1;
