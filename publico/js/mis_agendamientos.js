document.addEventListener('DOMContentLoaded', () => {
    const botonesCancelar = document.querySelectorAll('.btn-cancelar-reserva');

    botonesCancelar.forEach(boton => {
        boton.addEventListener('click', async function() {
            const idAgendamiento = this.dataset.id;
            const listItem = this.closest('.item-reserva'); // Para actualizar la UI

            if (!idAgendamiento) {
                console.error('No se encontró el ID del agendamiento para cancelar.');
                return;
            }

            // Confirmación antes de cancelar (opcional pero recomendado)
            if (!confirm(`¿Estás seguro de que quieres cancelar la reserva R-${idAgendamiento}?`)) {
                return;
            }

            try {
            
                const response = await fetch(`${BASE_URL}/reservas/cancelar/${idAgendamiento}`, {
                    method: 'POST', //  'DELETE' si el router lo soporta bien para formularios simples
                    headers: {
                        'Content-Type': 'application/json', 
                        'X-Requested-With': 'XMLHttpRequest' // Para identificar la petición AJAX en el servidor si es necesario
                    }
                    // body: JSON.stringify({ id_agendamiento: idAgendamiento }) // Si el ID va en el cuerpo
                });

                if (!response.ok) {
                    // Si la respuesta no es OK, intentar parsear como JSON si es posible para obtener el mensaje de error
                    let errorMsg = `Error del servidor: ${response.status}`;
                    try {
                        const errorData = await response.json();
                        errorMsg = errorData.mensaje || errorMsg;
                    } catch (e) {
                        // No es JSON, usar el texto de la respuesta si existe
                        const textError = await response.text();
                        if(textError) errorMsg = textError;
                    }
                    throw new Error(errorMsg);
                }

                const resultado = await response.json();

                if (resultado.exito) {
                    alert(resultado.mensaje);
                    // Actualizar la UI:
                    // Opción 1: Recargar la página
                    // window.location.reload(); 
                    // Opción 2: Modificar el DOM directamente (más elegante)
                    if (listItem) {
                        const estadoSpan = listItem.querySelector('.estado-reserva');
                        if (estadoSpan) {
                            estadoSpan.textContent = 'Cancelada';
                            estadoSpan.className = 'estado-reserva estado-cancelada';
                        }
                        this.remove(); // Eliminar el botón de cancelar
                    }
                } else {
                    alert('Error al cancelar: ' + (resultado.mensaje || 'No se pudo completar la cancelación.'));
                }

            } catch (error) {
                console.error('Error en la petición de cancelación:', error);
                alert('Ocurrió un error al intentar cancelar la reserva: ' + error.message);
            }
        });
    });
});