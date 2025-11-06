/*
Diseñadores de la base de datos ©TODOS LOS DERECHOS RESERVADOS
Barreto Basave Lizbeth
Sanchez Luna Alexis Sebastian
*/
/*Nota. No consdeirar borra la base de datos ya que si no no funcionara correctamente*/
drop database if exists IdentiQR; #Quedara como prueba
create database IdentiQR;
use IdentiQR;
/*Creación de las tablas*/

/*Asignación de usuarios y privilegios para usar en IdentiQR*/
/*
	create user 'DeptoVinculacion@localhost' identified by 'Vinculacion123_Access';
	create user 'DeptoDireAca@localhost' identified by 'DireccionAca123_Access';
	create user 'DeptoDDA@localhost' identified by 'DireccionDA123_Access';
	create user 'DeptoServEsco@localhost' identified by 'ServiciosEscolares123_Access';
	create user 'DeptoAE@localhost' identified by 'AsuntosEstudiantiles123_Access';
	create user 'DeptoMedico@localhost' identified by 'Medico123_Access';
    
    grant select, insert, update, delete on identiqr.departamento to 'DeptoVinculacion@localhost';
	grant select, insert, update, delete on identiqr.alumno to 'DeptoDireAca@localhost';
    grant select, insert, update, delete on identiqr.departamento to 'DeptoDDA@localhost';
    grant select, insert, update, delete on identiqr.departamento to 'DeptoServEsco@localhost';
    grant select, insert, update, delete on identiqr.departamento to 'DeptoAE@localhost';
    grant select, insert, update, delete on identiqr.departamento to 'DeptoMedico@localhost';
*/

#1. La tabla Departamento fungirá como identificador de los diferentes Direcciones dentro de la universidad
create table Departamento(
	idDepto int primary key auto_increment,
    Nombre varchar(60) not null,
    Descripcion varchar(100) not null
);
INSERT INTO `identiqr`.`Departamento` (`idDepto`, `Nombre`, `Descripcion`) VALUES ('1', 'Sin Dirección asociada', 'Sin dirección.');
INSERT INTO `identiqr`.`Departamento` (`idDepto`, `Nombre`, `Descripcion`) VALUES ('2', 'Direccion Academica', 'Dirección de carrera');
INSERT INTO `identiqr`.`Departamento` (`idDepto`, `Nombre`, `Descripcion`) VALUES ('3', 'Servicios Escolares', 'Administración de trámites académicos y servicios para los estudiantes');
INSERT INTO `identiqr`.`Departamento` (`idDepto`, `Nombre`, `Descripcion`) VALUES ('4', 'Dirección Desarrollo Academico', 'Gestión y mejora de los procesos académicos y pedagógicos (Tutoria)');
INSERT INTO `identiqr`.`Departamento` (`idDepto`, `Nombre`, `Descripcion`) VALUES ('5', 'Dirección Asuntos Estudiantiles', 'Coordinación de actividades y servicios para el bienestar estudiantil');
INSERT INTO `identiqr`.`Departamento` (`idDepto`, `Nombre`, `Descripcion`) VALUES ('6', 'Consultorio de atención de primer contacto', 'Consultoría y atención médica inicial para estudiantes');
INSERT INTO `identiqr`.`Departamento` (`idDepto`, `Nombre`, `Descripcion`) VALUES ('7', 'Vinculación', 'Conexión con empresas, organizaciones y otras instituciones educativas');
#INSERT INTO `identiqr`.`Departamento` (`idDepto`, `Nombre`, `Descripcion`) VALUES ('8', 'Biblioteca', 'Servicios de consulta y préstamo de material bibliográfico');

/*2. La tabla de usuario permitira registrar a los diferentes usuarios que tendrá el sistema. Queda aparte, es decir no será del todo relacional*/
-- Esta tabla almacena información de las personas que interactuarán con el sistema.
create table if not exists Usuario (
    id_usuario int primary key auto_increment,
    nombre varchar(60) not null,
    apellido_paterno varchar(50) not null,
    apellido_materno varchar(50) not null,
    genero varchar(45) default "Otro",
    usr varchar(45) not null,
    email varchar(60) unique not null, #UNIQUE es una restricción que posee SQL para imponer integridad en datos, evitando duplicidad. https://hightouch.com/sql-dictionary/sql-unique
    passw varchar(150) not null, # La contraseña debe ser hasheada
    rol varchar(50) not null, # Rol del usuario (ej. 'Administrador', 'Direccion_Academica', 'Vinculacion')
    idDepto int, # Clave foránea al departamento al que pertenece el usuario
    FechaRegistro datetime not null,
	foreign key (idDepto) references Departamento(idDepto) on update cascade on delete cascade
);

/*3. La tabla de ServicioTramite permitira ver los folio de los tramites o servicios que se ofrecen para cada departamento*/
create table ServicioTramite (
	idTramite int(4) zerofill primary key auto_increment,
    Descripcion varchar(45) not null,
    idDepto int,
    foreign key (idDepto) references Departamento(idDepto) on update cascade on delete cascade
);
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0001', 'Extracurricular', '5');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0002', 'Tutorias', '4');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0003', 'Reinscripcion', '3');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0004', 'Inscripcion', '3');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0005', 'Estancia I', '7');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0006', 'Estancia II', '7');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0007', 'Estadia', '7');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0008', 'Servicio Social', '7');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0009', 'Practicas Profesionales', '7');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0010', 'Reposicion Credencial', '3');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0011', 'Justificante', '2');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0012', 'Recursamiento', '2');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0013', 'Consulta medica', '6');
INSERT INTO `identiqr`.`serviciotramite` (`idTramite`, `Descripcion`, `idDepto`) VALUES ('0014', 'Constancias e historial', '3');

/*4. Tabla carrera funcionara como identificador por alumno de identificar a que ccarera estan y así*/
create table Carrera(
	idCarrera int primary key not null,
    descripcion text not null,
    planEstudios varchar(45) not null,
    idDepto int(3) not null,
    foreign key (idDepto) references Departamento(idDepto) on update cascade on delete cascade
);

INSERT INTO `identiqr`.`carrera` (`idCarrera`, `descripcion`, `planEstudios`, `idDepto`) VALUES ('1', 'ITI', 'ITI-H18', '2');
INSERT INTO `identiqr`.`carrera` (`idCarrera`, `descripcion`, `planEstudios`, `idDepto`) VALUES ('2', 'IID', 'TSU-DS-NM24', '2');
INSERT INTO `identiqr`.`carrera` (`idCarrera`, `descripcion`, `planEstudios`, `idDepto`) VALUES ('3', 'IET', 'IET-H18', '2');
INSERT INTO `identiqr`.`carrera` (`idCarrera`, `descripcion`, `planEstudios`, `idDepto`) VALUES ('4', 'ISE', 'TSU-RC-NM24', '2');
    
#5. Tabla para la creación del alumno. Aquí se almacenaran todos los datos de los alumnos de la institución
create table Alumno (
	Matricula varchar(15) primary key not null,
    Nombre varchar(60) not null,
    ApePat varchar(60) not null,
    ApeMat varchar(60) null,
    FechaNac date not null,
    FeIngreso date not null,
    Correo varchar(60) unique,
    Direccion varchar(60) not null,
    Telefono varchar(11) not null,
    Ciudad varchar(45) not null,
    Estado varchar(45) null,
    Genero varchar(10) default "Otro",
    idCarrera int not null,
    qrHash text null default "Pendiente", #Queda nulo porque se genera despues de registrar || longText || 
    fechaGenerarQR_1 date not null,
    fechaExpiracionQR_2 date,
    foreign key (idCarrera) references Carrera(idCarrera) on update cascade on delete cascade
);
#5. Tabla para la creación de la FICHA MEDICA del alumno. Aquí se almacenaran todos los datos de los alumnos de la institución, a su referencia
create table InformacionMedica(
	idInfoM int primary key auto_increment,
    Matricula varchar(15) not null,
    TipoSangre varchar(5) not null,
    Alergias text default "Sin_Alergias",
    contacto_emergencia varchar(15),
    fechaIngreso_InfoMed date,
    foreign key (Matricula) references Alumno(Matricula) on update cascade on delete cascade
);
#6. Tabla para el registro de servicio (Cuando se haga o el administrador agregue algun tramite/servicio que fue realizado).
create table RegistroServicio(
	FolioRegistro int(4) zerofill primary key auto_increment,
    Matricula varchar(15) not null,
    idTramite INT(4) zerofill NOT NULL,
    FechaHora datetime not null,
    descripcion text default "S/D", #PUEDE QUEDAR NULO
    estatusT varchar(45) default "Pendiente",
    foreign key (Matricula) references Alumno(Matricula) on update cascade on delete cascade,
    foreign key(idTramite) references ServicioTramite(idTramite) on update cascade on delete cascade
);


#7. Tabla para gestionar el historial de los movimientos que se hacen dentro de la base de datos
create table if not exists HistorialInfoQR(
	idHistorialInfoQR int(5) zerofill primary key not null auto_increment,
    fechaHora datetime not null,
    accionRealizada text null,
    descripcion text null,
    usuarioRealizado varchar(45) not null
);

/*		Implementación de disparadores					*/
delimiter //
create trigger IngresoUsuario before insert on Usuario
for each row
begin
	declare n,p,m varchar(10);
    set new.passw = MD5(new.passw);
    set new.FechaRegistro = now();
    set new.usr = upper(concat(left(new.nombre,1),left(new.apellido_paterno,1),right(new.apellido_materno,1),year(new.FechaRegistro),"-",substring(UUID(),1,2)));
end //

delimiter //
create trigger IngresoUsuarioAct before update on Usuario
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
end //

/*		Disparadores para anotar en las bitacoras		*/ 
delimiter //
create trigger Ing_Bita_Usuario after insert on Usuario
for each row
begin
	declare mensaje text;
    declare accionR varchar(20); #Registro,Modificación,Baja
    declare usR text;
    
    set mensaje = concat_ws(" ", "Usuario nuevo dado de alta.",new.nombre, new.email);
    set accionR = "Registro";
    set usR = concat_ws(" ", "Realizado por: ", user());
    
    
    insert into HistorialInfoQR values (0,now(),accionR,mensaje,usR);

end //
delimiter //
create trigger Act_Bita_Usuario after update on Usuario
for each row
begin
	declare mensaje text;
    declare accionR varchar(20); #Registro,Modificación,Baja
    declare usR text;
    
    set mensaje = concat_ws(" ", "Usuario actualizado datos: ",new.nombre, new.email, new.passw, "Anteriores: ", old.nombre,old.email,old.passw);
    set accionR = "Actualización";
    set usR = concat_ws(" ", "Realizado por: ", user());
    
    
    insert into HistorialInfoQR values (0,now(),accionR,mensaje,usR);

end //
delimiter //
create trigger Baja_Bita_Usuario after delete on Usuario
for each row
begin
	declare mensaje text;
    declare accionR varchar(20); #Registro,Modificación,Baja
    declare usR text;
    
    set mensaje = concat_ws(" ", "Usuario viejo dado de baja.",old.nombre, old.email);
    set accionR = "Baja";
    set usR = concat_ws(" ", "Realizado por: ", user());
    
    insert into HistorialInfoQR values (0,now(),accionR,mensaje,usR);
end //

/*Inserciones dentro de la tabla de Usuario*/
/*Usuarios generados para cada DIRECCIÓN*/
INSERT INTO `identiqr`.`usuario` (`id_usuario`, `nombre`, `apellido_paterno`, `apellido_materno`, `email`, `passw`, `rol`,`idDepto`) VALUES ('0', 'Identi', 'Q', 'R', 'identiqr.info@gmail.com', 'IdentiQR_Admin', 'Administrador',1); /*Admin*/
INSERT INTO `identiqr`.`usuario` (`id_usuario`, `nombre`, `apellido_paterno`, `apellido_materno`, `email`, `passw`, `rol`,`idDepto`) VALUES ('0', 'Identi', 'Q', 'R_Dir', 'identiQR.info_Dir@identiqr.com', 'IdentiQR_Dir', 'Administrativo_Direccion',2);/*Dirección - IQR2025-72*/
INSERT INTO `identiqr`.`usuario` (`id_usuario`, `nombre`, `apellido_paterno`, `apellido_materno`, `email`, `passw`, `rol`,`idDepto`) VALUES ('0', 'Identi', 'Q', 'R_ServEsco', 'identiQR.info_ServEsco@identiqr.com', 'IdentiQR_ServEsco', 'Administrativo_ServicioEsco',3);/*Serv Esco - IQO2025-72 */
INSERT INTO `identiqr`.`usuario` (`id_usuario`, `nombre`, `apellido_paterno`, `apellido_materno`, `email`, `passw`, `rol`,`idDepto`) VALUES ('0', 'Identi', 'Q', 'R_DDA', 'identiQR.info_DDA@identiqr.com', 'IdentiQR_DDA', 'Administrativo_DesaAca',4);/*DDA - IQA2025-72*/
INSERT INTO `identiqr`.`usuario` (`id_usuario`, `nombre`, `apellido_paterno`, `apellido_materno`, `email`, `passw`, `rol`,`idDepto`) VALUES ('0', 'Identi', 'Q', 'R_DAE', 'identiQR.info_DAE@identiqr.com', 'IdentiQR_DAE', 'Administrativo_DAE',5);/*DAE - IQE2025-72*/
INSERT INTO `identiqr`.`usuario` (`id_usuario`, `nombre`, `apellido_paterno`, `apellido_materno`, `email`, `passw`, `rol`,`idDepto`) VALUES ('0', 'Identi', 'Q', 'R_Med', 'identiQR.info_Med@identiqr.com', 'IdentiQR_Med', 'Administrativo_Medico',6);/*Med - IQD2025-72*/
INSERT INTO `identiqr`.`usuario` (`id_usuario`, `nombre`, `apellido_paterno`, `apellido_materno`, `email`, `passw`, `rol`,`idDepto`) VALUES ('0', 'Identi', 'Q', 'R_Vinc', 'identiQR.info_Vinc@identiqr.com', 'IdentiQR_Vinc', 'Administrativo_Vinculacion',7);/*Vinc - IQC2025-72*/

select * from departamento;
/* DISPARADOR PARA EL REGISTRO DE LA CONTRASEÑA INICIAL - Falta*/

/*Disparadores para agilizar el procedimiento de ASIGNACIÓNDE HASH a los alumnos*/
delimiter //
create trigger actHash_QR_Alumno before update on alumno
for each row
begin
	if ((old.qrHash != new.qrHash) and (new.qrHash != 'Pendiente')) then
        set new.fechaGenerarQR_1 = CURDATE();
    end if;
end //

/*Disparador para agilizar el procedimiento de asignación de DATOS a los tramites/servicios*/
delimiter //
create trigger regisServ_Tramite before insert on registroservicio
for each row
begin
	set new.FechaHora = now();
end //


/* Procedimiento para agilizar la consulta de los diferentes usuarios que tendrán acceso al sistema*/
delimiter //
create procedure Login(in us varchar(255), in pass text) 
begin
	declare pass_md5 text;
    
    set pass_md5 = md5(pass);
    
    select * from usuario where (usr = us or email = us) and passw = pass_md5; 
end //

/*Procedimiento para agilizar el registro de los usuarios*/
delimiter //
create procedure registrarUsuariosSP(in nombre varchar(60), in ApP varchar(45), in ApM varchar(45),in usr varchar(100), in email varchar(100), in passw varchar(255), in rol varchar(45), in idDepto int)
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
end //

/*PROCEDIMIENTO CON TRANSACCIÓN OARA AGILIZAR LA ELIMINACIÓN DE LOS USUARIOS*/
delimiter //
CREATE PROCEDURE darBajaUsuario(IN usuario VARCHAR(45), OUT eliminado INT)
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
END //

/*PROCEDIMIENTO CON TRASACCIÓN DISTRIBUIDA PARA AGILIZAR LA ELIMINACIÓN DE LSO USUARIOS*/
delimiter //
CREATE PROCEDURE darBajaAlumno(IN matr VARCHAR(45), OUT eliminado INT)
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
END //

/*PROCEDIMIENTO CON TRANSACCIÓN DISTRIBUIDA PARA AGILIZAR LA ELIMINACIÓN DE LOS TRAMITES*/


/*Funcion para agilizar la obtención de los cuatrimestres en los cuales se encuentra el alumno*/
delimiter //
create function calcCuatrimestre(feIngreso date) 
returns int
deterministic
begin
	declare m, c int; #Meses || Cuatrimestres
    set m = timestampdiff(month,feIngreso,curdate());
    set c = (truncate(m/4,0) + 1);
    
    return c;
end //