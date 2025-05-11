<?php $reservas = $datos_vista['reservas'] ?? []; ?>
<div class="contenedor-reservas">
    <h2 class="titulo-seccion">Mis Agendamientos</h2>
    <?php if (!empty($reservas)): ?>
        <ul class="lista-reservas">
        <?php foreach($reservas as $reserva): ?>
            <li class="item-reserva">
                <p><strong>ID Reserva:</strong> <?= htmlspecialchars($reserva['id']) ?></p>
                <p><strong>Docente ID:</strong> <?= htmlspecialchars($reserva['id_docente']) ?></p>
                <p><strong>Fecha:</strong> <?= htmlspecialchars($reserva['fecha']) ?></p>
                <p><strong>Hora:</strong> <?= htmlspecialchars($reserva['hora']) ?></p>
                <button class="btn-cancelar-reserva">Cancelar</button>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="sin-reservas">Aún no tienes agendamientos. <a href="<?= BASE_URL ?>/docentes">¡Busca un tutor ahora!</a></p>
    <?php endif; ?>
</div>
<style>
    .titulo-seccion { text-align: center; font-size: 2rem; margin-bottom: 25px; color: var(--color-tuttor-up-green); }
    .contenedor-reservas { max-width: 800px; margin: 20px auto; padding: 20px; background-color: rgba(37,41,48,0.5); border-radius: 8px; }
    .lista-reservas { list-style: none; padding: 0; }
    .item-reserva { background-color: var(--color-menu-background); padding: 15px; border-radius: 5px; margin-bottom: 10px; border: 1px solid var(--color-header-background); }
    .item-reserva p { margin: 5px 0; color: var(--text-color-light); }
    .item-reserva strong { color: var(--text-color-muted); }
    .btn-cancelar-reserva {
        background-color: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; float: right;
    }
    .sin-reservas { text-align: center; font-size: 1.1rem; color: var(--text-color-muted); padding: 20px; }
    .sin-reservas a { color: var(--color-tuttor-up-green); text-decoration: none; }
    .sin-reservas a:hover { text-decoration: underline; }
</style>