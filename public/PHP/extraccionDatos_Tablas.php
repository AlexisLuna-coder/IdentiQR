<?php
    function obtenerExtracurricular($descripcion) {
        // Extrae todo lo que esté entre corchetes []
        preg_match_all('/\[(.*?)\]/u', $descripcion, $matches);
        $arr = $matches[1] ?? [];

        // Recorremos las coincidencias buscando "extracurricular" en la misma o en la siguiente
        for ($i = 0; $i < count($arr); $i++) {
            $item = trim($arr[$i]);

            // Si el corchete contiene la palabra 'extracurricular'
            if (stripos($item, 'extracurricular') !== false) {
                // 1) Intentar extraer lo que venga después de separadores comunes (-, :, >)
                $after = preg_replace('/(?i).*extracurricular[\s\-\:\>\–]*\s*/u', '', $item);
                $after = trim($after, " \t\n\r\0\x0B-:>."); // recorta separadores sobrantes

                if ($after !== '') {
                    return $after;
                }

                // 2) Si no hay texto después, mirar el siguiente corchete (p. ej. [Extracurricular] [Básquetbol])
                if (isset($arr[$i + 1]) && trim($arr[$i + 1]) !== '') {
                    return trim($arr[$i + 1]);
                }

                // 3) Si nada de lo anterior, intentar una búsqueda más amplia en la descripción
                // buscar "Extracurricular" y palabras cercanas en la misma frase
                if (preg_match('/Extracurricular[\s\-\:\>]*([^\]\.\,\n]+)/iu', $descripcion, $m)) {
                    $cand = trim($m[1], " []\t\n\r\0\x0B-:.");
                    if ($cand !== '') return $cand;
                }

                // Si no se pudo, devolver "No especificado"
                return "No especificado";
            }
        }

        // Si no apareció la palabra en ningun corchete, intentar patrón alterno (p. ej. "de [Extracurricular-Cine debate]")
        if (preg_match('/Extracurricular[\s\-\:\>]*([A-Za-zÁÉÍÓÚáéíóúÑñ0-9\-\s]+)/u', $descripcion, $m)) {
            $cand = trim($m[1], " []\t\n\r\0\x0B-:.");
            if ($cand !== '') return $cand;
        }

        return "No especificado";
    }

    //Función para obtener el tutor
    function obtenerTutor($descripcion) {
        // Busca el texto dentro de <...> que esté después de la palabra "tutor"
        if (preg_match('/tutor(?:\/a)?:?\s*<([^>]+)>/iu', $descripcion, $coincidencia)) {
            return trim($coincidencia[1]);
        }

        // Si no se encuentra con la palabra tutor, toma el último <...> (que normalmente es el tutor)
        if (preg_match_all('/<([^>]+)>/u', $descripcion, $coincidencias)) {
            $ultimo = end($coincidencias[1]);
            return trim($ultimo);
        }

        return "No especificado";
    }
?>
