# +===================================================================
# |
# | Generado el 24-11-2025 a las 08:46:56 PM
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

INSERT INTO `alumno` VALUES ('BBLO230123', 'LIZBETH', 'BARRETO', 'BASAVE', '2005-11-03', '2023-09-01', 'BBLO230123@upemor.edu.mx', 'PRUEBA 1', '777-444-8585', 'JIUTEPEC', 'MORELOS', 'Femenino', '1', '08e1ec0b4126024f669f31189741f8f5b95050702343a64d63e0e9255fdd805e', '2025-11-24', '2026-03-24');
INSERT INTO `alumno` VALUES ('SLAO230036', 'ALEXIS SEBASTIAN', 'SANCHEZ', 'LUNA', '2005-10-27', '2023-09-01', 'SLAO230036@upemor.edu.mx', 'Calle Santa Maria Lt 11, Col El Porvenir, Jiutepec Mor.', '777-480-5924', 'Jiutepec', 'Mor.', 'Masculino', '1', '99c9f40dfbe507231cdfc5d547c6c102b9177ad20938f8652861a43d5c908eb9', '2025-11-24', '2026-03-24');

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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `historialinfoqr` VALUES ('00001', '2025-11-22 17:53:12', 'Registro', 'Usuario nuevo dado de alta. Identi identiqr.info@gmail.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00002', '2025-11-22 17:53:22', 'Registro', 'Usuario nuevo dado de alta. Identi identiQR.info_Dir@identiqr.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00003', '2025-11-22 17:53:33', 'Registro', 'Usuario nuevo dado de alta. Identi identiQR.info_ServEsco@identiqr.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00004', '2025-11-22 17:53:35', 'Registro', 'Usuario nuevo dado de alta. Identi identiQR.info_DDA@identiqr.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00005', '2025-11-22 17:53:38', 'Registro', 'Usuario nuevo dado de alta. Identi identiQR.info_DAE@identiqr.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00006', '2025-11-22 17:53:40', 'Registro', 'Usuario nuevo dado de alta. Identi identiQR.info_Med@identiqr.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00007', '2025-11-22 17:56:51', 'Registro', 'Usuario nuevo dado de alta. LIZBETH bblo230123@upemor.edu.mx', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00008', '2025-11-22 17:58:30', 'Actualización', 'Usuario actualizado datos:  LIZBETH bblo230123@upemor.edu.mx 295e30e57a7d852b8594041c78cf2e55 Anteriores:  LIZBETH bblo230123@upemor.edu.mx 304294d67f9c0088568b560b9537703f', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00009', '2025-11-22 18:00:05', 'Actualización', 'Usuario actualizado datos:  LIZBETH bblo230123@upemor.edu.mx 28c8edde3d61a0411511d3b1866f0636 Anteriores:  LIZBETH bblo230123@upemor.edu.mx 295e30e57a7d852b8594041c78cf2e55', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00010', '2025-11-22 18:02:54', 'Actualización', 'Usuario actualizado datos:  LIZBETH bblo230123@upemor.edu.mx 5fda55944571ad535865a8b9edc62a4a Anteriores:  LIZBETH bblo230123@upemor.edu.mx 28c8edde3d61a0411511d3b1866f0636', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00011', '2025-11-22 18:12:04', 'Actualización', 'Usuario actualizado datos:  LIZBETH bblo230123@upemor.edu.mx ec6a6536ca304edf844d1d248a4f08dc Anteriores:  LIZBETH bblo230123@upemor.edu.mx 5fda55944571ad535865a8b9edc62a4a', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00012', '2025-11-22 18:15:43', 'Actualización', 'Usuario actualizado datos:  LIZBETH bblo230123@upemor.edu.mx ec6a6536ca304edf844d1d248a4f08dc Anteriores:  LIZBETH bblo230123@upemor.edu.mx ec6a6536ca304edf844d1d248a4f08dc', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00013', '2025-11-22 18:18:58', 'Actualización', 'Usuario actualizado datos:  LIZBETH bblo230123@upemor.edu.mx ec6a6536ca304edf844d1d248a4f08dc Anteriores:  LIZBETH bblo230123@upemor.edu.mx ec6a6536ca304edf844d1d248a4f08dc', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00014', '2025-11-22 18:21:50', 'Actualización', 'Usuario actualizado datos:  LIZBETH bblo230123@upemor.edu.mx a8d38512fba9556dd4abf46a8e2b5c88 Anteriores:  LIZBETH bblo230123@upemor.edu.mx ec6a6536ca304edf844d1d248a4f08dc', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00015', '2025-11-22 18:21:59', 'Actualización', 'Usuario actualizado datos:  LIZBETH bblo230123@upemor.edu.mx ec6a6536ca304edf844d1d248a4f08dc Anteriores:  LIZBETH bblo230123@upemor.edu.mx a8d38512fba9556dd4abf46a8e2b5c88', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00016', '2025-11-22 18:24:14', 'Actualización', 'Usuario actualizado datos:  LIZBETH bblo230123@upemor.edu.mx 7dc0442bebf87dfd9dfaa5a4145f6065 Anteriores:  LIZBETH bblo230123@upemor.edu.mx ec6a6536ca304edf844d1d248a4f08dc', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00017', '2025-11-22 18:26:16', 'Baja', 'Usuario viejo dado de baja. LIZBETH bblo230123@upemor.edu.mx', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00018', '2025-11-22 18:26:51', 'Registro', 'Usuario nuevo dado de alta. Prueba1 prueba@gmail.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00019', '2025-11-22 18:29:57', 'Actualización', 'Usuario actualizado datos:  Prueba1 prueba@gmail.com 295e30e57a7d852b8594041c78cf2e55 Anteriores:  Prueba1 prueba@gmail.com 304294d67f9c0088568b560b9537703f', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00020', '2025-11-22 18:35:07', 'Actualización', 'Usuario actualizado datos:  Prueba1 prueba@gmail.com 304294d67f9c0088568b560b9537703f Anteriores:  Prueba1 prueba@gmail.com 295e30e57a7d852b8594041c78cf2e55', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00021', '2025-11-22 18:37:38', 'Actualización', 'Usuario actualizado datos:  Prueba1 prueba@gmail.com d8ca6948fe2374ff482515e8fcd502bb Anteriores:  Prueba1 prueba@gmail.com 304294d67f9c0088568b560b9537703f', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00022', '2025-11-22 18:42:05', 'Actualización', 'Usuario actualizado datos:  Prueba1 prueba@gmail.com 304294d67f9c0088568b560b9537703f Anteriores:  Prueba1 prueba@gmail.com d8ca6948fe2374ff482515e8fcd502bb', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00023', '2025-11-24 15:48:59', 'Registro', 'Usuario nuevo dado de alta. Roberto Enrique alexissanchezluna@outlook.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00024', '2025-11-24 16:28:26', 'Actualización', 'Usuario actualizado datos:  Roberto Enrique Josemail alexissanchezluna@outlook.com 81dc9bdb52d04dc20036dbd8313ed055 Anteriores:  Roberto Enrique alexissanchezluna@outlook.com 81dc9bdb52d04dc20036dbd8313ed055', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00025', '2025-11-24 16:29:28', 'Actualización', 'Usuario actualizado datos:  Roberto Enrique Josemail alexissanchezluna@outlook.com 81dc9bdb52d04dc20036dbd8313ed055 Anteriores:  Roberto Enrique Josemail alexissanchezluna@outlook.com 81dc9bdb52d04dc20036dbd8313ed055', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00026', '2025-11-24 19:26:34', 'Registro', 'Usuario nuevo dado de alta. ALEXIS SEBASTIAN alexissanchezluna5@gmail.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00027', '2025-11-24 19:30:04', 'Registro', 'Usuario nuevo dado de alta. ALEXIS SEBASTIAN alexissanchezluna@gmail.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00028', '2025-11-24 19:30:23', 'Baja', 'Usuario viejo dado de baja. ALEXIS SEBASTIAN alexissanchezluna@gmail.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00029', '2025-11-24 19:33:14', 'Baja', 'Usuario viejo dado de baja. Roberto Enrique Josemail alexissanchezluna@outlook.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00030', '2025-11-24 19:33:28', 'Registro', 'Usuario nuevo dado de alta. ALEXIS SEBASTIAN alexissanchezluna@outlook.com', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00031', '2025-11-24 19:34:33', 'Actualización', 'Usuario actualizado datos:  ALEXIS SEBASTIAN alexissanchezluna@outlook.com 81dc9bdb52d04dc20036dbd8313ed055 Anteriores:  ALEXIS SEBASTIAN alexissanchezluna@outlook.com 81dc9bdb52d04dc20036dbd8313ed055', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00032', '2025-11-24 19:34:59', 'Baja', 'Usuario viejo dado de baja. ALEXIS SEBASTIAN alexissanchezluna@outlook.com', 'Realizado por:  root@localhost');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `informacionmedica` VALUES ('6', 'SLAO230036', 'O+', 'S/A', '777-123-4567', '2025-11-24');
INSERT INTO `informacionmedica` VALUES ('7', 'BBLO230123', 'O-', 'MUCHAS', '777-969-1234', '2025-11-24');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `registroservicio` VALUES ('0008', 'BBLO230123', '0011', '2025-11-24 20:20:58', 'El Alumno [LIZBETH BARRETO BASAVE] con matrícula [BBLO230123] del cuatrimestre [7] de la carrera [ITI] solicitó un <JUSTIFICANTE> para el día [2025-11-24]. Requisitos o notas [Fue mi cumple]', 'Pendiente', 'BBLO230123-202524Jus-EFOD');
INSERT INTO `registroservicio` VALUES ('0009', 'BBLO230123', '0012', '2025-11-24 20:21:19', 'El Alumno [LIZBETH BARRETO BASAVE] con matrícula [BBLO230123] del cuatrimestre [7] de la carrera [ITI] solicitó un <RECURSAMIENTO> el día [2025-11-23]. Requisitos o notas [ABD, FPOO Y POO]', 'Pendiente', 'BBLO230123-202524Rec-FUGZ');

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuario` VALUES ('1', 'Identi', 'Q', 'R', 'Otro', 'IDQR2025-6A', 'identiqr.info@gmail.com', '16810c2c3c47c48bac34b13477334246', 'Administrador', '1', '2025-11-22 17:53:12');
INSERT INTO `usuario` VALUES ('2', 'Identi', 'Q', 'R_Dir', 'Otro', 'IDQR2025-70', 'identiQR.info_Dir@identiqr.com', '05bd8f267c49f809ab073d3be2523acb', 'Administrativo_Direccion', '2', '2025-11-22 17:53:22');
INSERT INTO `usuario` VALUES ('3', 'Identi', 'Q', 'R_ServEsco', 'Otro', 'IDQO2025-76', 'identiQR.info_ServEsco@identiqr.com', '485c23b3fb485647370b53f212bee674', 'Administrativo_ServicioEsco', '3', '2025-11-22 17:53:33');
INSERT INTO `usuario` VALUES ('4', 'Identi', 'Q', 'R_DDA', 'Otro', 'IDQA2025-78', 'identiQR.info_DDA@identiqr.com', '4195d5c2016569adde5a618cb2a70d0f', 'Administrativo_DesaAca', '4', '2025-11-22 17:53:35');
INSERT INTO `usuario` VALUES ('5', 'Identi', 'Q', 'R_DAE', 'Otro', 'IDQE2025-79', 'identiQR.info_DAE@identiqr.com', 'a0820dc6fe69a2d2896c2ccaf833c40d', 'Administrativo_DAE', '5', '2025-11-22 17:53:38');
INSERT INTO `usuario` VALUES ('6', 'Identi', 'Q', 'R_Med', 'Otro', 'IDQD2025-7A', 'identiQR.info_Med@identiqr.com', '0a7ca5f74a62338919049db9b8e67c58', 'Administrativo_Medico', '6', '2025-11-22 17:53:40');
INSERT INTO `usuario` VALUES ('8', 'Prueba1', 'Prueba', 'Piedra', 'Otro', 'PRPA2025-1D', 'prueba@gmail.com', '304294d67f9c0088568b560b9537703f', 'Administrador', '1', '2025-11-22 18:42:05');
INSERT INTO `usuario` VALUES ('15', 'ALEXIS SEBASTIAN', 'Sanchez', 'SANCHEZ LUNA', 'Masculino', 'ALSA2025-C7', 'alexissanchezluna5@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Administrador', '1', '2025-11-24 19:26:34');

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
    set new.usr = upper(concat(left(new.nombre,2),left(new.apellido_paterno,1),right(new.apellido_materno,1),year(new.FechaRegistro),"-",substring(UUID(),1,2)));
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
    
    
    insert into HistorialInfoQR values (null,now(),accionR,mensaje,usR);
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
    
    
    insert into HistorialInfoQR values (null,now(),accionR,mensaje,usR);
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
    
    insert into HistorialInfoQR values (null,now(),accionR,mensaje,usR);
end//
DELIMITER ;

SET FOREIGN_KEY_CHECKS=1;
