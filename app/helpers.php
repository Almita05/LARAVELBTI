<?php

if (!function_exists('has_perm')) {
    function has_perm($submodulo, $accion) {
        $rol = strtoupper(session('rol'));
        if ($rol === 'ADMIN') {
            return true;
        }
        if ($rol === 'DOCENTE') {
            return true;
        }
        $modulosValidos = session('modulos', []);
        if (is_string($modulosValidos)) {
            $modulosValidos = array_map('trim', explode(',', $modulosValidos));
        }
        
        // Compatibilidad: si el módulo completo está habilitado sin sufijo de acción
        if (in_array($submodulo, $modulosValidos)) {
            return true;
        }
        
        // Validación granular: submodulo:accion
        return in_array("{$submodulo}:{$accion}", $modulosValidos);
    }
}
