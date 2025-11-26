# +===================================================================
# |
# | Generado el 25-11-2025 a las 11:34:35 PM
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

INSERT INTO `alumno` VALUES ('AAJO252151', 'Jose Manuel', 'Arenal', 'Araujo', '2004-10-19', '2025-09-01', 'AAJO252151@upemor.edu.mx', 'Calle Prueba 8', '777-999-5685', 'Cuernavaca', 'Morelos', 'Masculino', '4', '677a7ea4ba94d7c04fe1d8e922e95d48f716493e077bbf2c5b9ca41e1671968d', '2025-11-25', '2026-03-25');
INSERT INTO `alumno` VALUES ('BBLO230123', 'Lizbeth', 'Barreto', 'Basave', '2005-11-03', '2023-09-01', 'BBLO230123@upemor.edu.mx', 'Calle Prueba 1', '777-111-2222', 'Jiutepec', 'Morelos', 'Femenino', '1', '249788bb469662119f141a0a7d116aed403274d455289a820ced3a9a98418957', '2025-11-25', '2026-03-25');
INSERT INTO `alumno` VALUES ('GSRO230490', 'Rocio Linette', 'Gomez', 'Salvador', '2005-01-01', '2023-09-01', 'GSRO230490@upemor.edu.mx', 'Calle Prueba 3', '777-858-9999', 'Emiliano Zapata', 'Morelos', 'Femenino', '1', 'dcdb8d5826f4a12c0302d5c85693b1f5bdb04de05f7f8198bcd107bce8d9b0eb', '2025-11-25', '2026-03-25');
INSERT INTO `alumno` VALUES ('MCJO231878', 'Jonathan Daniel', 'Martinez', 'Castrejon', '2004-02-01', '2023-09-01', 'MCJO231878@upemor.edu.mx', 'Calle Prueba 4', '777-968-5263', 'Emiliano Zapata', 'Morelos', 'Masculino', '1', '27f870bee9d2a251d42cbf025f6ca911bfd66f0a73ee459594002af2b2674666', '2025-11-25', '2026-03-25');
INSERT INTO `alumno` VALUES ('MDCO230011', 'Cynthia Jocelyn', 'Martinez', 'Delgado', '2005-06-01', '2023-09-01', 'MDCO230011@upemor.edu.mx', 'Calle Prueba 2', '777-555-5555', 'Jiutepec', 'Morelos', 'Femenino', '1', '387c930696ce21b469bded80287d49fb0bd36cda0c66a02ab5e96271b7645c9a', '2025-11-25', '2026-03-25');
INSERT INTO `alumno` VALUES ('ORJO230233', 'Jose Mariano', 'Ocampo', 'Romero', '2002-08-18', '2024-09-01', 'ORJO230233@upemor.edu.mx', 'Calle Prueba 5', '777-123-8594', 'Jiutepec', 'Morelos', 'Masculino', '2', 'e71f6165413f8839e0649f905782859025d69ad7d8cf82a25c370398ff6d6cf9', '2025-11-25', '2026-03-25');
INSERT INTO `alumno` VALUES ('rado252302', 'Diego Ivan', 'Rodriguez', 'Abonce', '2004-05-05', '2023-09-01', 'rado252302@upemor.edu.mx', 'Calle Prueba 6', '777-859-2541', 'Jiutepec', 'Morelos', 'Masculino', '3', '0a80edbbb4734d336270c0642e15e45f4893ca68908eb4d1291639641b8fd572', '2025-11-25', '2026-03-25');
INSERT INTO `alumno` VALUES ('SLAO230036', 'Alexis Sebastian', 'Sanchez', 'Luna', '2005-10-27', '2023-09-01', 'SLAO230036@upemor.edu.mx', 'Calle Santa Maria Lt 11, Col El Porvenir, Jiutepec Mor.', '777-480-5924', 'Jiutepec', 'Morelos', 'Masculino', '1', '76b7e472872d1a4540a98270923d979c962bc11277bd515625b99186337fdc30', '2025-11-25', '2026-03-25');
INSERT INTO `alumno` VALUES ('VMJO241284', 'Jesus', 'Vargas', 'QuitarApe', '2006-06-01', '2024-09-01', 'VMJO241284@upemor.edu.mx', 'Calle Prueba 7', '747-965-2653', 'Jiutepec', 'Morelos', 'Masculino', '2', '758022089806abdf69734109aa830c22e1c7e514b7b6c758a8932dbeeccc3c31', '2025-11-25', '2026-03-25');

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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
INSERT INTO `historialinfoqr` VALUES ('00033', '2025-11-25 18:32:50', 'Registro', 'Usuario nuevo dado de alta. Usuario Prueba bblo230123@upemor.edu.mx', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00034', '2025-11-25 18:33:23', 'Actualización', 'Usuario actualizado datos:  Lizbeth Prueba Usr bblo230123@upemor.edu.mx 0391393dc1fffa3708b60efc4b3cea92 Anteriores:  Usuario Prueba bblo230123@upemor.edu.mx 0391393dc1fffa3708b60efc4b3cea92', 'Realizado por:  root@localhost');
INSERT INTO `historialinfoqr` VALUES ('00035', '2025-11-25 18:33:38', 'Baja', 'Usuario viejo dado de baja. Lizbeth Prueba Usr bblo230123@upemor.edu.mx', 'Realizado por:  root@localhost');

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `informacionmedica` VALUES ('10', 'SLAO230036', 'O+', 'Sin alergias', '777-156-8683', '2025-11-25');
INSERT INTO `informacionmedica` VALUES ('11', 'BBLO230123', 'O-', 'Polen, Gatos, Polvo', '777-444-4444', '2025-11-25');
INSERT INTO `informacionmedica` VALUES ('12', 'MDCO230011', 'O-', 'Sin alergias', '777-222-1111', '2025-11-25');
INSERT INTO `informacionmedica` VALUES ('13', 'GSRO230490', 'AB+', 'Sin alergias', '777-888-5555', '2025-11-25');
INSERT INTO `informacionmedica` VALUES ('14', 'MCJO231878', 'B+', 'Polvo', '777-555-6912', '2025-11-25');
INSERT INTO `informacionmedica` VALUES ('15', 'ORJO230233', 'A-', 'Sin alergias', '777-458-2514', '2025-11-25');
INSERT INTO `informacionmedica` VALUES ('16', 'rado252302', 'B-', 'Sin alergias', '735-123-8569', '2025-11-25');
INSERT INTO `informacionmedica` VALUES ('17', 'VMJO241284', 'O-', 'Al polvo', '777-859-2145', '2025-11-25');
INSERT INTO `informacionmedica` VALUES ('18', 'AAJO252151', 'A-', 'Sin alergias', '777-480-5924', '2025-11-25');

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
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `registroservicio` VALUES ('0011', 'SLAO230036', '0011', '2025-11-25 21:47:47', 'El Alumno [Alexis Sebastian Sanchez Luna] con matrícula [SLAO230036] del cuatrimestre [7] de la carrera [ITI] solicitó un <JUSTIFICANTE> para el día [2025-11-25]. Requisitos o notas [Falta por excursión academica]', 'Pendiente', 'SLAO230036-202525Jus-JMKN');
INSERT INTO `registroservicio` VALUES ('0012', 'SLAO230036', '0012', '2025-11-25 21:48:12', 'El Alumno [Alexis Sebastian Sanchez Luna] con matrícula [SLAO230036] del cuatrimestre [7] de la carrera [ITI] solicitó un <RECURSAMIENTO> el día [2025-11-25]. Requisitos o notas [Recursamiento de LEA y INR]', 'Completado', 'SLAO230036-202525Rec-TTMF');
INSERT INTO `registroservicio` VALUES ('0013', 'BBLO230123', '0011', '2025-11-25 21:49:02', 'El Alumno [Lizbeth Barreto Basave] con matrícula [BBLO230123] del cuatrimestre [7] de la carrera [ITI] solicitó un <JUSTIFICANTE> para el día [2025-11-26]. Requisitos o notas [Llegada de Andres]', 'Pendiente', 'BBLO230123-202525Jus-KINQ');
INSERT INTO `registroservicio` VALUES ('0014', 'MDCO230011', '0011', '2025-11-25 21:50:04', 'El Alumno [Cynthia Jocelyn Martinez Delgado] con matrícula [MDCO230011] del cuatrimestre [7] de la carrera [ITI] solicitó un <JUSTIFICANTE> para el día [2025-11-24]. Requisitos o notas [Falta académica por excursión]', 'Pendiente', 'MDCO230011-202525Jus-YXQL');
INSERT INTO `registroservicio` VALUES ('0015', 'MDCO230011', '0012', '2025-11-25 21:50:26', 'El Alumno [Cynthia Jocelyn Martinez Delgado] con matrícula [MDCO230011] del cuatrimestre [7] de la carrera [ITI] solicitó un <RECURSAMIENTO> el día [2025-11-10]. Requisitos o notas [FPOO,POO]', 'Pendiente', 'MDCO230011-202525Rec-QFFJ');
INSERT INTO `registroservicio` VALUES ('0016', 'GSRO230490', '0011', '2025-11-25 21:50:55', 'El Alumno [Rocio Linette Gomez Salvador] con matrícula [GSRO230490] del cuatrimestre [7] de la carrera [ITI] solicitó un <JUSTIFICANTE> para el día [2025-11-26]. Requisitos o notas [Representativo de Baile]', 'Pendiente', 'GSRO230490-202525Jus-JDQT');
INSERT INTO `registroservicio` VALUES ('0017', 'MCJO231878', '0012', '2025-11-25 21:51:29', 'El Alumno [Jonathan Daniel Martinez Castrejon] con matrícula [MCJO231878] del cuatrimestre [7] de la carrera [ITI] solicitó un <RECURSAMIENTO> el día [2025-11-25]. Requisitos o notas [Funciones Matematicas]', 'Pendiente', 'MCJO231878-202525Rec-VAQE');
INSERT INTO `registroservicio` VALUES ('0018', 'ORJO230233', '0012', '2025-11-25 21:52:07', 'El Alumno [Jose Mariano Ocampo Romero] con matrícula [ORJO230233] del cuatrimestre [4] de la carrera [IID] solicitó un <RECURSAMIENTO> el día [2025-10-01]. Requisitos o notas [FPOO,POO,ABD,BDD,PRO]', 'Pendiente', 'ORJO230233-202525Rec-TLWD');
INSERT INTO `registroservicio` VALUES ('0019', 'rado252302', '0011', '2025-11-25 21:52:33', 'El Alumno [Diego Ivan Rodriguez Abonce] con matrícula [rado252302] del cuatrimestre [7] de la carrera [IET] solicitó un <JUSTIFICANTE> para el día [2025-11-28]. Requisitos o notas [Faltare por análisis]', 'Pendiente', 'rado252302-202525Jus-FZIT');
INSERT INTO `registroservicio` VALUES ('0020', 'VMJO241284', '0012', '2025-11-25 21:52:53', 'El Alumno [Jesus Vargas QuitarApe] con matrícula [VMJO241284] del cuatrimestre [4] de la carrera [IID] solicitó un <RECURSAMIENTO> el día [2025-11-25]. Requisitos o notas [POO]', 'Pendiente', 'VMJO241284-202525Rec-LCEQ');
INSERT INTO `registroservicio` VALUES ('0021', 'AAJO252151', '0011', '2025-11-25 21:53:17', 'El Alumno [Jose Manuel Arenal Araujo] con matrícula [AAJO252151] del cuatrimestre [1] de la carrera [ISE] solicitó un <JUSTIFICANTE> para el día [2025-11-26]. Requisitos o notas [Iré a drogarme]', 'Pendiente', 'AAJO252151-202525Jus-QQGK');
INSERT INTO `registroservicio` VALUES ('0022', 'SLAO230036', '0006', '2025-11-25 22:26:33', 'El alumno [Alexis Sebastian Sanchez Luna] [Matrícula: SLAO230036], de <7°> Cuatri en la carrera <ITI>, solicitó el trámite de [Estancia II - Solicitó continuar con su segunda estancia profesional - Fecha: <2025-11-25>]. Documentos entregados: [Constancia, CartaMotivos, CartaAceptacion]. Documentos finales correctos y adecuados: [Si].', 'Pendiente', 'SLAO230036-202525Est-HFDB');
INSERT INTO `registroservicio` VALUES ('0023', 'BBLO230123', '0006', '2025-11-25 22:27:01', 'El alumno [Lizbeth Barreto Basave] [Matrícula: BBLO230123], de <7°> Cuatri en la carrera <ITI>, solicitó el trámite de [Estancia II - Solicitó continuar con su segunda estancia profesional - Fecha: <2025-11-25>]. Documentos entregados: [Constancia, Comprobante, CartaAceptacion, Otro]. Documentos finales correctos y adecuados: [No].', 'Rechazado', 'BBLO230123-202525Est-QPYX');
INSERT INTO `registroservicio` VALUES ('0024', 'MDCO230011', '0005', '2025-11-25 22:27:25', 'El alumno [Cynthia Jocelyn Martinez Delgado] [Matrícula: MDCO230011], de <7°> Cuatri en la carrera <ITI>, solicitó el trámite de [Estancia I - Solicitó iniciar su primera estancia profesional - Fecha: <2025-11-25>]. Documentos entregados: [INE, Constancia, Comprobante, CartaAceptacion]. Documentos finales correctos y adecuados: [Si].', 'Pendiente', 'MDCO230011-202525Est-DJJV');
INSERT INTO `registroservicio` VALUES ('0025', 'GSRO230490', '0007', '2025-11-25 22:28:03', 'El alumno [Rocio Linette Gomez Salvador] [Matrícula: GSRO230490], de <7°> Cuatri en la carrera <ITI>, solicitó el trámite de [Estadía - Solicitó registrar su estadía profesional - Fecha: <2025-11-25>]. Documentos entregados: [CartaMotivos, CartaAceptacion]. Documentos finales correctos y adecuados: [Si].', 'Pendiente', 'GSRO230490-202525Est-PJBG');
INSERT INTO `registroservicio` VALUES ('0026', 'MCJO231878', '0008', '2025-11-25 22:28:29', 'El alumno [Jonathan Daniel Martinez Castrejon] [Matrícula: MCJO231878], de <7°> Cuatri en la carrera <ITI>, solicitó el trámite de [Prácticas profesionales - Solicitó realizar sus prácticas profesionales - Fecha: <2025-11-25>]. Documentos entregados: [INE, CartaMotivos]. Documentos finales correctos y adecuados: [No].', 'Pendiente', 'MCJO231878-202525Ser-NHXP');
INSERT INTO `registroservicio` VALUES ('0027', 'ORJO230233', '0009', '2025-11-25 22:28:54', 'El alumno [Jose Mariano Ocampo Romero] [Matrícula: ORJO230233], de <4°> Cuatri en la carrera <IID>, solicitó el trámite de [Servicio social - Solicitó iniciar su servicio social - Fecha: <2025-11-25>]. Documentos entregados: [INE, Constancia]. Documentos finales correctos y adecuados: [Si].', 'Pendiente', 'ORJO230233-202525Pra-LPPD');
INSERT INTO `registroservicio` VALUES ('0028', 'rado252302', '0005', '2025-11-25 22:29:17', 'El alumno [Diego Ivan Rodriguez Abonce] [Matrícula: rado252302], de <7°> Cuatri en la carrera <IET>, solicitó el trámite de [Estancia I - Solicitó iniciar su primera estancia profesional - Fecha: <2025-11-25>]. Documentos entregados: [Constancia]. Documentos finales correctos y adecuados: [Si].', 'Pendiente', 'rado252302-202525Est-QBIO');
INSERT INTO `registroservicio` VALUES ('0029', 'VMJO241284', '0009', '2025-11-25 22:29:37', 'El alumno [Jesus Vargas QuitarApe] [Matrícula: VMJO241284], de <4°> Cuatri en la carrera <IID>, solicitó el trámite de [Servicio social - Solicitó iniciar su servicio social - Fecha: <2025-11-25>]. Documentos entregados: [PlanActividades]. Documentos finales correctos y adecuados: [No].', 'Pendiente', 'VMJO241284-202525Pra-RDSB');
INSERT INTO `registroservicio` VALUES ('0030', 'AAJO252151', '0008', '2025-11-25 22:30:02', 'El alumno [Jose Manuel Arenal Araujo] [Matrícula: AAJO252151], de <1°> Cuatri en la carrera <ISE>, solicitó el trámite de [Prácticas profesionales - Solicitó realizar sus prácticas profesionales - Fecha: <2025-11-25>]. Documentos entregados: [PlanActividades]. Documentos finales correctos y adecuados: [Si].', 'Pendiente', 'AAJO252151-202525Ser-GHRP');
INSERT INTO `registroservicio` VALUES ('0031', 'SLAO230036', '0002', '2025-11-25 22:34:06', 'El Alumno [Alexis Sebastian Sanchez Luna] con matrícula [SLAO230036] del cuatrimestre [7] con el Plan de estudio <ITI-H18> durante el periodo [7O-2025] asistió [Asitio a tutorias GRUPALES: -  10 veces] y [Tutorias - Individuales: 0 veces] con el tutor/a: <Dr. Juan Paulo Sánchez Hernández>', 'Completado', 'SLAO230036-202525Tut-EUKT');
INSERT INTO `registroservicio` VALUES ('0032', 'BBLO230123', '0002', '2025-11-25 22:34:23', 'El Alumno [Lizbeth Barreto Basave] con matrícula [BBLO230123] del cuatrimestre [7] con el Plan de estudio <ITI-H18> durante el periodo [7O-2025] asistió [Asitio a tutorias GRUPALES: -  10 veces] y [Tutorias - Individuales: 0 veces] con el tutor/a: <Dr. Juan Paulo Sánchez Hernández>', 'Pendiente', 'BBLO230123-202525Tut-RUYK');
INSERT INTO `registroservicio` VALUES ('0033', 'MDCO230011', '0002', '2025-11-25 22:34:42', 'El Alumno [Cynthia Jocelyn Martinez Delgado] con matrícula [MDCO230011] del cuatrimestre [7] con el Plan de estudio <ITI-H18> durante el periodo [7O-2025] asistió [Asitio a tutorias GRUPALES: -  9 veces] y [Tutorias - Individuales: 1 veces] con el tutor/a: <Dr. Juan Paulo Sánchez Hernández>', 'Pendiente', 'MDCO230011-202525Tut-AXMQ');
INSERT INTO `registroservicio` VALUES ('0034', 'GSRO230490', '0002', '2025-11-25 22:35:03', 'El Alumno [Rocio Linette Gomez Salvador] con matrícula [GSRO230490] del cuatrimestre [7] con el Plan de estudio <ITI-H18> durante el periodo [7O-2025] asistió [Asitio a tutorias GRUPALES: -  8 veces] y [Tutorias - Individuales: 2 veces] con el tutor/a: <Dr. Juan Paulo Sánchez Hernández>', 'Pendiente', 'GSRO230490-202525Tut-RAYU');
INSERT INTO `registroservicio` VALUES ('0035', 'MCJO231878', '0002', '2025-11-25 22:35:24', 'El Alumno [Jonathan Daniel Martinez Castrejon] con matrícula [MCJO231878] del cuatrimestre [7] con el Plan de estudio <ITI-H18> durante el periodo [7O-2025] asistió [Asitio a tutorias GRUPALES: -  10 veces] y [Tutorias - Individuales: 2 veces] con el tutor/a: <Dr. Juan Paulo Sánchez Hernández>', 'Pendiente', 'MCJO231878-202525Tut-ZZWK');
INSERT INTO `registroservicio` VALUES ('0036', 'ORJO230233', '0002', '2025-11-25 22:36:53', 'El Alumno [Jose Mariano Ocampo Romero] con matrícula [ORJO230233] del cuatrimestre [4] con el Plan de estudio <TSU-DS-NM24> durante el periodo [4O-2025] asistió [Asitio a tutorias GRUPALES: -  5 veces] y [Tutorias - Individuales: 4 veces] con el tutor/a: <Dra. Deny Lizbeth Hernández Rabadán>', 'Pendiente', 'ORJO230233-202525Tut-POWU');
INSERT INTO `registroservicio` VALUES ('0037', 'rado252302', '0002', '2025-11-25 22:37:11', 'El Alumno [Diego Ivan Rodriguez Abonce] con matrícula [rado252302] del cuatrimestre [7] con el Plan de estudio <IET-H18> durante el periodo [7O-2025] asistió [Asitio a tutorias GRUPALES: -  10 veces] y [Tutorias - Individuales: 0 veces] con el tutor/a: <Juan Carlos Bodoque>', 'Pendiente', 'rado252302-202525Tut-LRAB');
INSERT INTO `registroservicio` VALUES ('0038', 'VMJO241284', '0002', '2025-11-25 22:37:31', 'El Alumno [Jesus Vargas QuitarApe] con matrícula [VMJO241284] del cuatrimestre [4] con el Plan de estudio <TSU-DS-NM24> durante el periodo [4O-2025] asistió [Asitio a tutorias GRUPALES: -  10 veces] y [Tutorias - Individuales: 0 veces] con el tutor/a: <Pedrito Sola>', 'Pendiente', 'VMJO241284-202525Tut-UYKF');
INSERT INTO `registroservicio` VALUES ('0039', 'AAJO252151', '0002', '2025-11-25 22:37:51', 'El Alumno [Jose Manuel Arenal Araujo] con matrícula [AAJO252151] del cuatrimestre [1] con el Plan de estudio <TSU-RC-NM24> durante el periodo [1O-2025] asistió [Asitio a tutorias GRUPALES: -  10 veces] y [Tutorias - Individuales: 4 veces] con el tutor/a: <Dr. Miguel Ángel Ruiz Jaimes>', 'Pendiente', 'AAJO252151-202525Tut-HQIU');
INSERT INTO `registroservicio` VALUES ('0040', 'SLAO230036', '0001', '2025-11-25 22:43:30', 'El Alumno [Alexis Sebastian Sanchez Luna] con matrícula [SLAO230036] del cuatrimestre [7] de la carrera [ITI] <Solicitó unirse al extracurricular> de [Extracurricular-Fubtol]. Datos Médicos [$Sin alergias- Sangre: O+]', 'Pendiente', 'SLAO230036-202525Ext-QOXT');
INSERT INTO `registroservicio` VALUES ('0041', 'BBLO230123', '0001', '2025-11-25 22:43:49', 'El Alumno [Lizbeth Barreto Basave] con matrícula [BBLO230123] del cuatrimestre [7] de la carrera [ITI] <Solicitó unirse al extracurricular> de [Extracurricular-Voleibol]. Datos Médicos [$Polen, Gatos, Polvo- Sangre: O-]', 'Pendiente', 'BBLO230123-202525Ext-TPQK');
INSERT INTO `registroservicio` VALUES ('0042', 'MDCO230011', '0001', '2025-11-25 22:44:08', 'El Alumno [Cynthia Jocelyn Martinez Delgado] con matrícula [MDCO230011] del cuatrimestre [7] de la carrera [ITI] <Solicitó unirse al extracurricular> de [Extracurricular-Pasaporte literario]. Datos Médicos [$Sin alergias- Sangre: O-]', 'Pendiente', 'MDCO230011-202525Ext-JLDI');
INSERT INTO `registroservicio` VALUES ('0043', 'GSRO230490', '0001', '2025-11-25 22:44:31', 'El Alumno [Rocio Linette Gomez Salvador] con matrícula [GSRO230490] del cuatrimestre [7] de la carrera [ITI] <Solicitó unirse al extracurricular> de [Extracurricular-Latín clásico]. Datos Médicos [$Sin alergias- Sangre: AB+]', 'Pendiente', 'GSRO230490-202525Ext-EXBN');
INSERT INTO `registroservicio` VALUES ('0044', 'MCJO231878', '0001', '2025-11-25 22:45:00', 'El Alumno [Jonathan Daniel Martinez Castrejon] con matrícula [MCJO231878] del cuatrimestre [7] de la carrera [ITI] <Solicitó unirse al extracurricular> de [Extracurricular-Latín clásico]. Datos Médicos [$Polvo- Sangre: B+]', 'Pendiente', 'MCJO231878-202525Ext-BFWS');
INSERT INTO `registroservicio` VALUES ('0045', 'ORJO230233', '0001', '2025-11-25 22:45:14', 'El Alumno [Jose Mariano Ocampo Romero] con matrícula [ORJO230233] del cuatrimestre [4] de la carrera [IID] <Solicitó unirse al extracurricular> de [Extracurricular-Cine debate]. Datos Médicos [$Sin alergias- Sangre: A-]', 'Pendiente', 'ORJO230233-202525Ext-BEVQ');
INSERT INTO `registroservicio` VALUES ('0046', 'rado252302', '0001', '2025-11-25 22:45:36', 'El Alumno [Diego Ivan Rodriguez Abonce] con matrícula [rado252302] del cuatrimestre [7] de la carrera [IET] <Solicitó unirse al extracurricular> de [Extracurricular-Básquetbol]. Datos Médicos [$Sin alergias- Sangre: B-]', 'Pendiente', 'rado252302-202525Ext-GZGF');
INSERT INTO `registroservicio` VALUES ('0047', 'VMJO241284', '0001', '2025-11-25 22:46:17', 'El Alumno [Jesus Vargas QuitarApe] con matrícula [VMJO241284] del cuatrimestre [4] de la carrera [IID] <Solicitó unirse al extracurricular> de [Extracurricular-Dibujo]. Datos Médicos [$Al polvo- Sangre: O-]', 'Pendiente', 'VMJO241284-202525Ext-QJZS');
INSERT INTO `registroservicio` VALUES ('0048', 'AAJO252151', '0001', '2025-11-25 22:46:32', 'El Alumno [Jose Manuel Arenal Araujo] con matrícula [AAJO252151] del cuatrimestre [1] de la carrera [ISE] <Solicitó unirse al extracurricular> de [Extracurricular-Canto]. Datos Médicos [$Sin alergias- Sangre: A-]', 'Completado', 'AAJO252151-202525Ext-YOWQ');
INSERT INTO `registroservicio` VALUES ('0049', 'SLAO230036', '0013', '2025-11-25 22:55:10', 'El Alumno [Alexis Sebastian Sanchez Luna] con matrícula [SLAO230036] del [7] Cuatri de la carrera [ITI] <Solicitó una> [Cita Médica: - el día <2025-11-05>]. Datos médicos [Sangre: O+ - Alergias: Sin alergias - Altura: 170.00 - Peso: 51.000 - Temperatura: 36.00°C]. Notas Adicionales: [Ojos rojos]', 'Pendiente', 'SLAO230036-202525Con-FJEV');
INSERT INTO `registroservicio` VALUES ('0050', 'BBLO230123', '0013', '2025-11-25 22:55:34', 'El Alumno [Lizbeth Barreto Basave] con matrícula [BBLO230123] del [7] Cuatri de la carrera [ITI] <Solicitó una> [Cita Médica: - el día <2025-11-25>]. Datos médicos [Sangre: O- - Alergias: Polen, Gatos, Polvo - Altura: 171.00 - Peso: 61.000 - Temperatura: 36.00°C]. Notas Adicionales: [Ojos rojos]', 'Pendiente', 'BBLO230123-202525Con-PAKX');
INSERT INTO `registroservicio` VALUES ('0051', 'MDCO230011', '0013', '2025-11-25 22:55:59', 'El Alumno [Cynthia Jocelyn Martinez Delgado] con matrícula [MDCO230011] del [7] Cuatri de la carrera [ITI] <Solicitó una> [Cita Médica: - el día <2025-11-25>]. Datos médicos [Sangre: O- - Alergias: Sin alergias - Altura: 145.00 - Peso: 41.000 - Temperatura: 37.00°C]. Notas Adicionales: [Colicos]', 'Pendiente', 'MDCO230011-202525Con-ZVFN');
INSERT INTO `registroservicio` VALUES ('0052', 'GSRO230490', '0013', '2025-11-25 22:56:29', 'El Alumno [Rocio Linette Gomez Salvador] con matrícula [GSRO230490] del [7] Cuatri de la carrera [ITI] <Solicitó una> [Cita Médica: - el día <2025-11-25>]. Datos médicos [Sangre: AB+ - Alergias: Sin alergias - Altura: 171.00 - Peso: 75.000 - Temperatura: 38.00°C]. Notas Adicionales: [Cólicos]', 'Pendiente', 'GSRO230490-202525Con-JNMW');
INSERT INTO `registroservicio` VALUES ('0053', 'MCJO231878', '0013', '2025-11-25 22:56:57', 'El Alumno [Jonathan Daniel Martinez Castrejon] con matrícula [MCJO231878] del [7] Cuatri de la carrera [ITI] <Solicitó una> [Cita Médica: - el día <2025-10-25>]. Datos médicos [Sangre: B+ - Alergias: Polvo - Altura: 175.00 - Peso: 85.000 - Temperatura: 35.00°C]. Notas Adicionales: [Dengue]', 'Pendiente', 'MCJO231878-202525Con-SDNH');
INSERT INTO `registroservicio` VALUES ('0054', 'ORJO230233', '0013', '2025-11-25 22:57:29', 'El Alumno [Jose Mariano Ocampo Romero] con matrícula [ORJO230233] del [4] Cuatri de la carrera [IID] <Solicitó una> [Cita Médica: - el día <2025-11-25>]. Datos médicos [Sangre: A- - Alergias: Sin alergias - Altura: 170.00 - Peso: 65.000 - Temperatura: 37.00°C]. Notas Adicionales: [Cólicos]', 'Pendiente', 'ORJO230233-202525Con-XVKO');
INSERT INTO `registroservicio` VALUES ('0055', 'rado252302', '0013', '2025-11-25 22:58:01', 'El Alumno [Diego Ivan Rodriguez Abonce] con matrícula [rado252302] del [7] Cuatri de la carrera [IET] <Solicitó una> [Cita Médica: - el día <2025-11-25>]. Datos médicos [Sangre: B- - Alergias: Sin alergias - Altura: 25.00 - Peso: 85.000 - Temperatura: 36.00°C]. Notas Adicionales: [Temperatura]', 'Pendiente', 'rado252302-202525Con-SFYD');
INSERT INTO `registroservicio` VALUES ('0056', 'VMJO241284', '0013', '2025-11-25 22:58:35', 'El Alumno [Jesus Vargas QuitarApe] con matrícula [VMJO241284] del [4] Cuatri de la carrera [IID] <Solicitó una> [Cita Médica: - el día <2025-11-25>]. Datos médicos [Sangre: O- - Alergias: Al polvo - Altura: 175.00 - Peso: 41.000 - Temperatura: 75.00°C]. Notas Adicionales: [Bajo de peso]', 'Completado', 'VMJO241284-202525Con-DPPG');
INSERT INTO `registroservicio` VALUES ('0057', 'SLAO230036', '0003', '2025-11-25 23:22:03', 'El Alumno [Alexis Sebastian Sanchez Luna] con matrícula [SLAO230036] y correo [SLAO230036@upemor.edu.mx], inscrito en el 7 Cuatri de [ITI], <7O-2025> |PERIODO: <Solicitó el tramite> [Tramite de Servicios escolares - 3] el día <2025-11-25> |Motivo adicional: [NO APLICA] | Monto pagado: [$750.00] | Método de pago: [TDD]. Requerimientos extras: <8 Cuatri>', 'Pendiente', 'SLAO230036-202525Rei-AVAS');
INSERT INTO `registroservicio` VALUES ('0058', 'BBLO230123', '0004', '2025-11-25 23:22:33', 'El Alumno [Lizbeth Barreto Basave] con matrícula [BBLO230123] y correo [BBLO230123@upemor.edu.mx], inscrito en el 7 Cuatri de [ITI], <7O-2025> |PERIODO: <Solicitó el tramite> [Tramite de Servicios escolares - 3] el día <2025-11-25> |Motivo adicional: [NO APLICA] | Monto pagado: [$1000.00] | Método de pago: [TDC]. Requerimientos extras: <1 Cuatri>', 'Pendiente', 'BBLO230123-202525Ins-IKBA');
INSERT INTO `registroservicio` VALUES ('0059', 'MDCO230011', '0010', '2025-11-25 23:23:02', 'El Alumno [Cynthia Jocelyn Martinez Delgado] con matrícula [MDCO230011] y correo [MDCO230011@upemor.edu.mx], inscrito en el 7 Cuatri de [ITI], <7O-2025> |PERIODO: <Solicitó el tramite> [Tramite de Servicios escolares - 3] el día <2025-11-25> |Motivo adicional: [OTROS] | Monto pagado: [$65.00] | Método de pago: [Deposito]. Requerimientos extras: <Perdí mi credencial>', 'Pendiente', 'MDCO230011-202525Rep-PUHA');
INSERT INTO `registroservicio` VALUES ('0060', 'GSRO230490', '0014', '2025-11-25 23:23:32', 'El Alumno [Rocio Linette Gomez Salvador] con matrícula [GSRO230490] y correo [GSRO230490@upemor.edu.mx], inscrito en el 7 Cuatri de [ITI], <7O-2025> |PERIODO: <Solicitó el tramite> [Tramite de Servicios escolares - 3] el día <2025-11-25> |Motivo adicional: [BECAS] | Monto pagado: [$110.00] | Método de pago: [TDD]. Requerimientos extras: <Beca Benito Juarez\r\n>', 'Pendiente', 'GSRO230490-202525Con-QTWY');
INSERT INTO `registroservicio` VALUES ('0061', 'MCJO231878', '0003', '2025-11-25 23:23:56', 'El Alumno [Jonathan Daniel Martinez Castrejon] con matrícula [MCJO231878] y correo [MCJO231878@upemor.edu.mx], inscrito en el 7 Cuatri de [ITI], <7O-2025> |PERIODO: <Solicitó el tramite> [Tramite de Servicios escolares - 3] el día <2025-11-25> |Motivo adicional: [NO APLICA] | Monto pagado: [$850.00] | Método de pago: [Transferencia]. Requerimientos extras: <8 Cuatri>', 'Pendiente', 'MCJO231878-202525Rei-XGNU');
INSERT INTO `registroservicio` VALUES ('0062', 'ORJO230233', '0014', '2025-11-25 23:24:21', 'El Alumno [Jose Mariano Ocampo Romero] con matrícula [ORJO230233] y correo [ORJO230233@upemor.edu.mx], inscrito en el 4 Cuatri de [IID], <4O-2025> |PERIODO: <Solicitó el tramite> [Tramite de Servicios escolares - 3] el día <2025-11-25> |Motivo adicional: [BAJA_DEFINITIVA] | Monto pagado: [$65.00] | Método de pago: [TDD]. Requerimientos extras: <Para ver mis reprobadas>', 'Pendiente', 'ORJO230233-202525Con-SRJS');
INSERT INTO `registroservicio` VALUES ('0063', 'rado252302', '0004', '2025-11-25 23:24:48', 'El Alumno [Diego Ivan Rodriguez Abonce] con matrícula [rado252302] y correo [rado252302@upemor.edu.mx], inscrito en el 7 Cuatri de [IET], <7O-2025> |PERIODO: <Solicitó el tramite> [Tramite de Servicios escolares - 3] el día <2025-11-25> |Motivo adicional: [OTROS] | Monto pagado: [$1000.00] | Método de pago: [TDC]. Requerimientos extras: <1 CUATRI>', 'Completado', 'rado252302-202525Ins-LYJY');
INSERT INTO `registroservicio` VALUES ('0064', 'VMJO241284', '0010', '2025-11-25 23:25:13', 'El Alumno [Jesus Vargas QuitarApe] con matrícula [VMJO241284] y correo [VMJO241284@upemor.edu.mx], inscrito en el 4 Cuatri de [IID], <4O-2025> |PERIODO: <Solicitó el tramite> [Tramite de Servicios escolares - 3] el día <2025-11-25> |Motivo adicional: [CERTIFICACION_COMPETENCIAS] | Monto pagado: [$150.00] | Método de pago: []. Requerimientos extras: <PARA APROBAR INGLES>', 'Pendiente', 'VMJO241284-202525Rep-RZWJ');
INSERT INTO `registroservicio` VALUES ('0065', 'AAJO252151', '0010', '2025-11-25 23:25:45', 'El Alumno [Jose Manuel Arenal Araujo] con matrícula [AAJO252151] y correo [AAJO252151@upemor.edu.mx], inscrito en el 1 Cuatri de [ISE], <1O-2025> |PERIODO: <Solicitó el tramite> [Tramite de Servicios escolares - 3] el día <2025-11-25> |Motivo adicional: [TITULACION] | Monto pagado: [$150.00] | Método de pago: [Transferencia]. Requerimientos extras: <PARA GRADUARME>', 'Pendiente', 'AAJO252151-202525Rep-VXBP');

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
    SET cuatri = CalcCuatrimestre(feIngreso);
    CASE 
        WHEN MONTH(CURDATE()) BETWEEN 1 AND 4 THEN 
            SET periodo = 'I';   -- Invierno
        WHEN MONTH(CURDATE()) BETWEEN 5 AND 8 THEN 
            SET periodo = 'P';   -- Primavera
        WHEN MONTH(CURDATE()) BETWEEN 9 AND 12 THEN 
            SET periodo = 'O';   -- Otoño
    END CASE;
    SET anio = YEAR(CURDATE());
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
            (
                (opc = 1 AND rs.FechaHora BETWEEN f1 AND f2)
                OR
                (opc = 2 AND a.Genero = g)
            )
		AND st.idDepto = idD
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
