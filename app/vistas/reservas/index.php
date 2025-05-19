<?php 
$reservas = $datos_vista['reservas'] ?? []; 
$mensaje_reserva = $datos_vista['mensaje_reserva'] ?? null;
?>
<div class="contenedor-reservas">
    <h2 class="titulo-seccion"><?= htmlspecialchars($datos_vista['titulo_seccion'] ?? 'Mis Agendamientos') ?></h2>

    <?php if ($mensaje_reserva): ?>
        <div class="mensaje-reserva <?= htmlspecialchars($mensaje_reserva['tipo']) ?>">
            <?= htmlspecialchars($mensaje_reserva['texto']) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($reservas)): ?>
        <ul class="lista-reservas">
        <?php foreach($reservas as $reserva): ?>
            <li class="item-reserva">
                <div class="reserva-info">
                    <p><strong>ID Reserva:</strong> R-<?= htmlspecialchars($reserva['id_agendamiento']) // CORREGIDO ?></p>
                    <p><strong>Docente:</strong> <?= htmlspecialchars($reserva['nombre_docente']) // Nombre del docente ?></p>
                    <p><strong>Materia:</strong> <?= htmlspecialchars($reserva['materia'] ?? 'No especificada') // Materia ?></p>
                    <p><strong>Fecha:</strong> <?= htmlspecialchars(date("d/m/Y", strtotime($reserva['fecha_tutoria']))) // CORREGIDO y formateado ?></p>
                    <p><strong>Hora:</strong> <?= htmlspecialchars($reserva['bloque_horario']) // CORREGIDO ?></p>
                    <p><strong>Estado:</strong> <span class="estado-reserva estado-<?= strtolower(htmlspecialchars($reserva['estado'])) ?>"><?= ucfirst(htmlspecialchars($reserva['estado'])) // Estado ?></span></p>
                    <p><strong>Agendado el:</strong> <?= htmlspecialchars(date("d/m/Y H:i", strtotime($reserva['fecha_creacion']))) // Fecha de creación formateada ?></p>
                </div>
                <div class="reserva-acciones">
                    <?php if (strtolower($reserva['estado']) === 'confirmada' || strtolower($reserva['estado']) === 'pendiente'): ?>
                        <!-- El botón cancelar para crear la  propia lógica JS y endpoint PHP -->
                        <button class="btn-accion-reserva btn-cancelar-reserva" data-id="<?= htmlspecialchars($reserva['id_agendamiento']) ?>">Cancelar</button>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="sin-reservas">Aún no tienes agendamientos. <a href="<?= BASE_URL ?>/docentes">¡Busca un tutor ahora!</a></p>
    <?php endif; ?>
</div>
<style>
    /*  estilos (los puse aqui por que causan conflicto con los generales ) */
    .titulo-seccion { text-align: center; font-size: 2rem; margin-bottom: 25px; color: var(--color-tuttor-up-green); }
    .contenedor-reservas { max-width: 800px; margin: 20px auto; padding: 20px; background-color: rgba(37,41,48,0.5); border-radius: 8px; }
    
    .mensaje-reserva {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
        text-align: center;
    }
    .mensaje-reserva.exito {
        background-color: rgba(var(--color-tuttor-up-green-rgb), 0.2);
        color: var(--color-tuttor-up-green);
        border: 1px solid var(--color-tuttor-up-green);
    }
    .mensaje-reserva.error {
        background-color: rgba(255, 0, 0, 0.1);
        color: #ff6b6b; /* Un rojo claro */
        border: 1px solid #ff6b6b;
    }

    .lista-reservas { list-style: none; padding: 0; }
    .item-reserva { 
        background-color: var(--color-menu-background); 
        padding: 15px 20px; 
        border-radius: 6px; 
        margin-bottom: 15px; 
        border-left: 5px solid var(--color-tuttor-up-green); 
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        transition: box-shadow 0.2s ease;
    }
    .item-reserva:hover {
        box-shadow: 0 0 10px rgba(var(--color-tuttor-up-green-rgb), 0.3);
    }
    .reserva-info p { margin: 6px 0; color: var(--text-color-light); font-size: 0.95rem; }
    .reserva-info strong { color: var(--text-color-muted); min-width: 120px; display: inline-block;}

    .estado-reserva {
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: capitalize;
    }
    .estado-confirmada { background-color: var(--color-tuttor-up-green); color: var(--text-color-dark); }
    .estado-pendiente { background-color: #ffc107; color: var(--text-color-dark); } /* Amarillo para pendiente */
    .estado-cancelada { background-color: #6c757d; color: var(--text-color-light); } /* Gris para cancelada */
    .estado-completada { background-color: #17a2b8; color: var(--text-color-light); } /* Azul info para completada */


    .reserva-acciones { margin-left: 20px; flex-shrink: 0; } /* Evita que el botón se encoja */
    .btn-accion-reserva {
        color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem;
        transition: opacity 0.2s ease;
    }
    .btn-cancelar-reserva { background-color: #dc3545; /* Rojo bootstrap danger */ }
    .btn-accion-reserva:hover { opacity: 0.8; }

    .sin-reservas { text-align: center; font-size: 1.1rem; color: var(--text-color-muted); padding: 30px 20px; }
    .sin-reservas a { color: var(--color-tuttor-up-green); text-decoration: none; font-weight: 500; }
    .sin-reservas a:hover { text-decoration: underline; }

    @media (max-width: 600px) {
        .item-reserva {
            flex-direction: column;
            align-items: stretch;
        }
        .reserva-acciones {
            margin-left: 0;
            margin-top: 10px;
            text-align: right;
        }
        .reserva-info strong {
            min-width: 100px; /* Reducir para móvil */
        }
    }
</style>