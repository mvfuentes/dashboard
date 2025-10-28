<?php
// api.php

header('Content-Type: application/json');

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}

$data = array(
    'memory_used_php' => 'N/A',
    'memory_total_system' => 'N/A',
    'disk_free' => 'N/A',
    'disk_total' => 'N/A',
    'network_traffic' => 'N/A', 
    'cpu_usage' => 'N/A', 
    'error' => null
);

try {
    // 1. Memoria utilizada por el script PHP
    $data['memory_used_php'] = formatBytes(memory_get_usage(true));

    // 2. Espacio en disco
    $disk_path = dirname(__FILE__); // Ruta del directorio actual
    $disk_free_space = disk_free_space($disk_path);
    $disk_total_space = disk_total_space($disk_path);

    $data['disk_free'] = formatBytes($disk_free_space);
    $data['disk_total'] = formatBytes($disk_total_space);

    // 3. Memoria Total del Sistema (Más complejo, intentaremos con `free -m` en Linux)
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') { // Si es Linux
        $output = null;
        $return_var = null;
        // shell_exec es peligroso si no se sanitizan las entradas. Aquí no hay entradas.
        // Asegúrate de que PHP tiene permiso para ejecutar `free -m`
        exec('free -m', $output, $return_var); 
        if ($return_var === 0 && isset($output[1])) {
            $mem_info = preg_split('/\s+/', $output[1]);
            if (isset($mem_info[1])) { // Total memory in MB
                $data['memory_total_system'] = formatBytes($mem_info[1] * 1024 * 1024);
            }
        }
    } elseif (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { // Si es Windows
        // En Windows es más complejo obtener la RAM total desde PHP sin WMI o comandos específicos
        // Para simplificar, lo dejaremos como 'N/A' o puedes usar un valor fijo para demostración.
        $data['memory_total_system'] = 'Windows: No directo'; 
    } else {
        $data['memory_total_system'] = 'SO Desconocido';
    }


    // 4. Tráfico de Red y CPU (Simulados para este ejemplo)
    // Para datos reales, necesitarías un agente en el servidor o acceso a /proc/net/dev (Linux)
    // o APIs del sistema operativo, lo cual está fuera del alcance de PHP directo.
    $data['network_traffic'] = round(rand(10, 1000) / 100, 2) . ' Mbps';
    $data['cpu_usage'] = rand(5, 75) . '%'; // Simulado
    
} catch (Exception $e) {
    $data['error'] = $e->getMessage();
}

echo json_encode($data);
?>