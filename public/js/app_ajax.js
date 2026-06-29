// ============================================
// APPCOM - FUNCIONES AJAX
// ============================================

// Cargar contenido sin recargar página
function cargarPagina(url, destino) {
    if (!destino) destino = '#mainContent';
    
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'html',
        beforeSend: function() {
            $(destino).html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2">Cargando...</p></div>');
        },
        success: function(response) {
            $(destino).html(response);
            // Actualizar URL en navegador (sin recargar)
            if (history.pushState) {
                history.pushState(null, null, url);
            }
            // Actualizar menú activo
            actualizarMenuActivo(url);
        },
        error: function() {
            $(destino).html('<div class="alert alert-danger">Error al cargar la página</div>');
        }
    });
}

// Actualizar menú activo
function actualizarMenuActivo(url) {
    $('.menu-item').removeClass('active');
    $('.menu-item[data-page="' + url + '"]').addClass('active');
}

// ============================================
// EVENTOS GLOBALES
// ============================================
$(document).ready(function() {
    // Clic en items del menú (para carga AJAX)
    $('.menu-item[data-page]').on('click', function(e) {
        // Solo si no es un submenú
        if (!$(this).data('toggle')) {
            e.preventDefault();
            var url = $(this).attr('href');
            if (url) {
                cargarPagina(url);
            }
        }
    });
});
