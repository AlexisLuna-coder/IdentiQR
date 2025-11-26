<?php
    #Prueba para ver si se realiza el login - Usando la clase Controlador.php
    require __DIR__ . '/../../Controllers/Controlador.php';
    $unControlador = new Controlador();

    // Datos de prueba
    $usr = "alexissanchezluna5@gmail.com"; #Usando este NO DEJA (NO EXISTE - MODIFICAR EL CALL)
    $usr2 = "ALSAA2025CF";
    $pw = "27Deoctubre*";

    if ($unControlador->loginUsuario($usr, $pw)) {
        echo "✅ Usuario logueado correctamente";
    } else {
        echo "❌ Usuario o contraseña incorrectos";
    }
    
?>
