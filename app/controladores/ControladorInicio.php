<?php
// app/controladores/ControladorInicio.php
class ControladorInicio {
    public function index() {
        $datos_para_vista['titulo_pagina'] = "Bienvenido a TUTTOR-UP";
        $datos_para_vista['controlador_actual'] = 'ControladorInicio'; // Para el menú activo
        $datos_para_vista['metodo_actual'] = 'index';

        $vista_contenido = ROOT_PATH . '/app/vistas/inicio/index.php';
        require_once ROOT_PATH . '/app/vistas/layout_principal.php';
    }
}
?>