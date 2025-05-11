<?php
// app/controladores/ControladorReservas.php
class ControladorReservas {
    public function index() {
        // Lógica para mostrar agendamientos, quizás del usuario logueado
        // require_once ROOT_PATH . '/app/modelos/ModeloReservas.php';
        // $modelo = new ModeloReservas();
        // $datos_vista['reservas'] = $modelo->obtenerPorUsuarioId($_SESSION['usuario_id'] ?? 0);

        $datos_layout['titulo_pagina'] = "Mis Agendamientos";
        $datos_layout['controlador_actual'] = 'ControladorReservas';
        $datos_layout['metodo_actual'] = 'index';
        // $datos_layout['js_especifico'] = ['reservas_interacciones.js']; // Si es necesario
        $datos_layout['datos_vista'] = [ /* 'reservas' => $datos_vista['reservas'] ?? [] */ ];


        $vista_contenido_path = ROOT_PATH . '/app/vistas/reservas/index.php';
        
        extract($datos_layout);
        $vista_contenido = $vista_contenido_path;
        require_once ROOT_PATH . '/app/vistas/layout_principal.php';
    }
}
?>