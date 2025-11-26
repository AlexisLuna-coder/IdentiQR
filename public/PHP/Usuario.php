<?php
    /*Creamos la clase para el manejo de POO de los objetos tipo ALUMNO*/
    class Usuario {

        private $id_usuario; 
        private $nombre;
        private $apellido_paterno; 
        private $apellido_materno;
        private $genero;
        private $usr; 
        private $email; 
        private $passw; 
        private $rol; 
        private $idDepto;
        private $FechaRegistro; 

        public function __construct($nombre, $apellido_paterno, $apellido_materno, $genero, $email, $passw, $rol, $idDepto) {
            $this->id_usuario = null; // Se asigna null inicialmente, ya que se generará automáticamente en la base de datos
            $this->nombre = $nombre;
            $this->apellido_paterno = $apellido_paterno;
            $this->apellido_materno = $apellido_materno;
            $this->genero = $genero;
            $this->usr = "Pendiente"; // Valor predeterminado
            $this->email = $email;
            $this->passw = $passw;
            $this->rol = $rol;
            $this->idDepto = $idDepto;
        }

        // Método para rellenar después de la BD
        public function cargarPendiente($id, $usr, $fechaRegistro) {
            $this->id_usuario = $id;
            $this->usr = $usr;
            $this->fechaRegistro = $fechaRegistro;
        }

        // Getters y Setters
        public function getIdUsuario() {
            return $this->id_usuario;
        }
        public function getNombre() {
            return $this->nombre;
        }
        public function getApellidoPaterno() {
            return $this->apellido_paterno;
        }
        public function getApellidoMaterno() {
            return $this->apellido_materno;
        }
        public function getGenero(){
            return $this->genero;
        }
        public function getUsr() {
            return $this->usr;
        }
        public function getEmail() {
            return $this->email;
        }
        public function getPassw() {
            return $this->passw;
        }
        public function getRol() {
            return $this->rol;
        }
        public function getIdDepto() {
            return $this->idDepto;
        }
        public function getFechaRegistro() {
            return $this->FechaRegistro;
        }
        public function setIdUsuario($id_usuario) {
            $this->id_usuario = $id_usuario;
        }
        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }
        public function setApellidoPaterno($apellido_paterno) {
            $this->apellido_paterno = $apellido_paterno;
        }
        public function setApellidoMaterno($apellido_materno) {
            $this->apellido_materno = $apellido_materno;
        }
        public function setGenero($genero){
            $this->genero = $genero;
        }
        public function setUsr($usr) {
            $this->usr = $usr;
        }
        public function setEmail($email) {
            $this->email = $email;
        }
        public function setPassw($passw) {
            $this->passw = $passw;
        }
        public function setRol($rol) {
            $this->rol = $rol;
        }
        public function setIdDepto($idDepto) {
            $this->idDepto = $idDepto;
        }
        public function setFechaRegistro($FechaRegistro) {
            $this->FechaRegistro = $FechaRegistro;
        }
    }
?>