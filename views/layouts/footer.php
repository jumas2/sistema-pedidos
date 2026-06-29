    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts del proyecto -->
<script src="<?= BASE_URL ?>public/js/app.js"></script>
<script src="<?= BASE_URL ?>public/js/pedidos.js"></script>
<script src="<?= BASE_URL ?>public/js/alertas.js"></script>

<script>
// ============================================
// TOGGLE SUBMENÚ CON ANIMACIÓN SUAVE
// ============================================
function toggleSubmenu(element) {
    event.preventDefault();
    var parent = element.closest('.menu-item');
    
    // Cerrar otros submenús abiertos (opcional)
    // var siblings = parent.parentElement.querySelectorAll('.menu-item.open');
    // siblings.forEach(function(item) {
    //     if (item !== parent) {
    //         item.classList.remove('open');
    //     }
    // });
    
    parent.classList.toggle('open');
}

// ============================================
// EFECTO DE ONDA AL HACER CLICK (Ripple)
// ============================================
document.querySelectorAll('.menu-item > a, .submenu li a').forEach(function(el) {
    el.addEventListener('click', function(e) {
        if (this.getAttribute('onclick')) return;
        var ripple = document.createElement('span');
        ripple.style.position = 'absolute';
        ripple.style.borderRadius = '50%';
        ripple.style.background = 'rgba(255,255,255,0.15)';
        ripple.style.transform = 'scale(0)';
        ripple.style.animation = 'ripple 0.6s ease-out';
        ripple.style.width = '60px';
        ripple.style.height = '60px';
        ripple.style.left = (e.clientX - this.getBoundingClientRect().left - 30) + 'px';
        ripple.style.top = (e.clientY - this.getBoundingClientRect().top - 30) + 'px';
        ripple.style.pointerEvents = 'none';
        this.style.position = 'relative';
        this.style.overflow = 'hidden';
        this.appendChild(ripple);
        setTimeout(function() { ripple.remove(); }, 600);
    });
});

// Agregar animación ripple al CSS
var styleSheet = document.createElement("style");
styleSheet.textContent = `
@keyframes ripple {
    to { transform: scale(4); opacity: 0; }
}
`;
document.head.appendChild(styleSheet);

// ============================================
// DETECTAR SI ESTÁ EN MÓVIL PARA COLLAPSE
// ============================================
function checkMobile() {
    if (window.innerWidth <= 992) {
        document.querySelector('.sidebar').classList.add('collapsed');
    } else {
        document.querySelector('.sidebar')?.classList.remove('collapsed');
    }
}
checkMobile();
window.addEventListener('resize', checkMobile);

console.log('🚀 Sidebar moderno cargado correctamente');
</script>

</body>
</html>

<script>
// ============================================
// FUNCIONES DE CONFIRMACIÓN MODERNAS
// ============================================


function confirmarAccion(mensaje, titulo = '¿Confirmar acción?', boton = 'Sí, continuar') {
    event.preventDefault();
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        confirmButtonText: boton,
        cancelButtonText: 'Cancelar',
        background: '#ffffff',
        backdrop: 'rgba(15, 23, 42, 0.4)',
        customClass: {
            popup: 'rounded-3 shadow-lg',
            title: 'fw-bold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = event.currentTarget.href;
        }
    });
    return false;
}

console.log('✅ Funciones de confirmación modernas cargadas');
</script>

<script>
// ============================================
// FUNCIONES DE CONFIRMACIÓN MODERNAS
// ============================================

function confirmEliminar(mensaje) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: mensaje || 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        background: '#ffffff',
        backdrop: 'rgba(15, 23, 42, 0.4)',
        customClass: {
            popup: 'rounded-3 shadow-lg',
            title: 'fw-bold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = event.currentTarget.href;
        }
    });
    return false;
}

function confirmAction(mensaje, titulo = '¿Confirmar acción?', boton = 'Sí, continuar') {
    event.preventDefault();
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        confirmButtonText: boton,
        cancelButtonText: 'Cancelar',
        background: '#ffffff',
        backdrop: 'rgba(15, 23, 42, 0.4)',
        customClass: {
            popup: 'rounded-3 shadow-lg',
            title: 'fw-bold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = event.currentTarget.href;
        }
    });
    return false;
}

console.log('✅ Funciones de confirmación modernas cargadas');
</script>

<script>
// ============================================
// CONFIRMAR LIBERACIÓN DE VEHÍCULO
// ============================================

</script>

<script>
</script>
