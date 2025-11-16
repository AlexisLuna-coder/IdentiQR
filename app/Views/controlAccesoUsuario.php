<?php
    session_start();
    if (!isset($_SESSION['rol'])) {
        header('Location: login.php');
        exit;
    }
    // $rol = $_SESSION['rol']; // STRING

    //ARREGLO
    // Variables necesarias
    $email        = $_SESSION['email'] ?? 'No definido';
    $rolDetectado = $_SESSION['rol'] ?? 'No definido';
    $usr          = $_SESSION['usr'] ?? 'No definido';
    $idUsuario    = $_SESSION['idUsuario'] ?? null;
    // Destinos según rol
    $rol = $rolDetectado;
    $destinos = [
        "Administrador" => "GestionesAdministradorG.php",
        "Administrativo_Vinculacion" => "dirVinculacion/GestionesAdmin_Vinculacion.php",
        "Administrativo_ServicioEsco" => "dirServEsco/GestionesAdmin_ServEsco.php",
        "Administrativo_DesaAca" => "dirDDA/GestionesAdmin_DesaAca.php",
        "Administrativo_DAE" => "dirDAE/GestionesAdmin_DAE.php",
        "Administrativo_Direccion" => "dirDirAca/GestionesAdmin_Direccion.php",
        "Administrativo_Medico" => "dirMedica/GestionesAdmin_Medico.php"
    ];
    $urlDestino = $destinos[$rol] ?? '/IdentiQR/index.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirigiendo... - IdentiQR</title>
    <link rel="shortcut icon" href="/IdentiQR/public/Media/img/Favicon.ico" type="image/x-icon"> <!--FAVICON-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!--FALTA IMPLEMENTAR EL CÓDIGO EN CSS (ARCH. APARTE)-->
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    /* Reset */
    body {
        font-family: 'Inter', sans-serif;
        background: #f4f4f4;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        color: #333;
    }

    /* Contenedor principal */
    .redirect-wrapper {
        width: 100%;
        max-width: 700px;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    /* Cabecera */
    .redirect-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #6f42c1; /* Morado */
        padding: 18px 28px;
    }
    .header-title {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #ffffff;
        font-weight: 700;
        font-size: 1.3rem;
    }
    .header-title img {
        width: 45px;
        height: 45px;
    }
    .btn-regresar {
        background-color: #ffffff;
        color: #6f42c1;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-regresar:hover {
        background-color: #e5e5e5;
    }

    /* Contenido */
    .redirect-container {
        background: #ffffff;
        padding: 36px 40px;
        text-align: center;
        border-radius: 0 0 14px 14px;
    }
    .redirect-content h1 {
        font-size: 1.9rem;
        font-weight: 700;
        color: #ffc107; /* Amarillo */
        margin-bottom: 10px;
    }
    .redirect-content .subtitle {
        font-size: 1rem;
        color: #444;
        margin-bottom: 30px;
    }

    /* Tarjetas de info */
    .info-cards {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 16px;
        margin-bottom: 35px;
        flex-wrap: wrap;
    }
    .info-card {
        flex: 1;
        min-width: 150px;
        background: #ffffff;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        text-align: center;
    }
    .info-card span {
        display: block;
        font-size: 0.85rem;
        color: #777;
        margin-bottom: 6px;
    }
    .info-card strong {
        font-size: 1.1rem;
        font-weight: 600;
        color: #6f42c1; /* Morado */
    }
    .info-arrow {
        font-size: 2rem;
        color: #dc3545; /* Rojo */
    }

    /* Lista de roles */
    .roles-list-container h3 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #555;
        text-transform: uppercase;
        margin-bottom: 14px;
    }
    .roles-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .role-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f9f9f9;
        margin-bottom: 8px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.95rem;
    }
    .role-item i {
        font-size: 1.2rem;
        color: #dc3545; /* Rojo */
        width: 22px;
        text-align: center;
    }

    /* Footer */
    .redirect-footer {
        background-color: #6f42c1; /* Morado */
        padding: 22px 36px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 0 0 14px 14px;
    }
    .footer-note {
        color: #ffffff;
        font-size: 0.9rem;
    }
    .btn-continuar {
        background-color: #6f42c1; /* Morado */
        color: #ffffff;
        border: none;
        padding: 12px 28px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
    }
    .btn-continuar:hover {
        background-color: #8c63e1;
    }
    </style>
    <script>
        // Redirigir automáticamente después de 3 segundos
        setTimeout(function(){
            window.location.href = "<?php echo $urlDestino; ?>";
        }, 1500);
    </script>
</head>
<body>

    <main class="redirect-wrapper">
        <div class="redirect-container">
            
            <header class="redirect-header">
                <div class="header-title">
                    <i class="bi bi-shield-check"></i>
                    <img src="/IdentiQR/public/Media/img/IdentiQR-Eslogan-Fondo.png" alt="Logo IdentiQR" weight = "80" height="80">
                    <span>IdentiQR</span>
                </div>
                <a href="login.php" class="btn-regresar">Regresar</a> 
            </header>

            <section class="redirect-content">
                <h1>Redirigiendo por rol</h1>
                <p class="subtitle">Autenticación exitosa. Estamos dirigiéndote al área correspondiente según tu rol.</p>

                <div class="info-cards">
                    <div class="info-card">
                        <span>Cuenta verificada</span>
                        <strong><?php echo $usr; ?></strong>
                    </div>
                    
                    <i class="bi bi-arrow-right-circle-fill info-arrow"></i>

                    <div class="info-card">
                        <span>Rol detectado</span>
                        <strong><?php echo $rolDetectado; ?></strong>
                    </div>
                </div>

                <div class="roles-list-container">
                    <h3>Posibles destinos en el sistema</h3>
                    <ul class="roles-list">
                        <li class="role-item">
                            <i class="bi bi-person-gear"></i>
                            <span>Administrador</span>
                        </li>
                        <li class="role-item">
                            <i class="bi bi-arrows-angle-expand"></i>
                            <span>Vinculación</span>
                        </li>
                        <li class="role-item">
                            <i class="bi bi-card-checklist"></i>
                            <span>Servicios Escolares</span>
                        </li>
                        <li class="role-item">
                            <i class="bi bi-journals"></i>
                            <span>Desarrollo Académico</span>
                        </li>
                        <li class="role-item">
                            <i class="bi bi-people"></i>
                            <span>Asuntos Estudiantiles</span>
                        </li>
                        <li class="role-item">
                            <i class="bi bi-building"></i>
                            <span>Dirección Académica</span>
                        </li>
                        <li class="role-item">
                            <i class="bi bi-clipboard2-pulse"></i>
                            <span>Consultorio Médico</span>
                        </li>
                    </ul>
                </div>

            </section>
        </div>
        
        <footer class="redirect-footer">
            <p class="footer-note">Si no corresponde tu rol, contacta al administrador del sistema.</p>
            <a href="<?php echo $urlDestino; ?>" class="btn-continuar">Continuar</a>
        </footer>
    </main>
    
</body>
</html>