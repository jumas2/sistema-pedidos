// ============================================
// ALERTAS MODERNAS CON SWEETALERT2
// ============================================

function mostrarAlerta(tipo, titulo, mensaje, tiempo = 3000) {
    const iconos = {
        success: 'success',
        error: 'error',
        warning: 'warning',
        info: 'info',
        question: 'question'
    };
    
    const colores = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6',
        question: '#8b5cf6'
    };
    
    Swal.fire({
        icon: iconos[tipo] || 'info',
        title: titulo,
        text: mensaje,
        timer: tiempo,
        timerProgressBar: true,
        showConfirmButton: true,
        confirmButtonColor: colores[tipo] || '#4f46e5',
        confirmButtonText: 'Aceptar',
        background: '#ffffff',
        backdrop: 'rgba(15, 23, 42, 0.4)',
        customClass: {
            popup: 'rounded-3 shadow-lg',
            title: 'fw-bold',
            confirmButton: 'px-4 py-2 rounded-pill fw-bold'
        }
    });
}

// Alerta de éxito
function alertaExito(mensaje, titulo = '¡Éxito!') {
    mostrarAlerta('success', titulo, mensaje);
}

// Alerta de error
function alertaError(mensaje, titulo = '¡Error!') {
    mostrarAlerta('error', titulo, mensaje);
}

// Alerta de advertencia
function alertaAdvertencia(mensaje, titulo = '¡Atención!') {
    mostrarAlerta('warning', titulo, mensaje);
}

// Alerta de información
function alertaInfo(mensaje, titulo = 'Información') {
    mostrarAlerta('info', titulo, mensaje);
}

// Alerta de confirmación con opciones
function alertaConfirmacion(mensaje, titulo = '¿Estás seguro?', textoBoton = 'Sí, continuar') {
    return Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        confirmButtonText: textoBoton,
        cancelButtonText: 'Cancelar',
        background: '#ffffff',
        backdrop: 'rgba(15, 23, 42, 0.4)',
        customClass: {
            popup: 'rounded-3 shadow-lg',
            title: 'fw-bold'
        }
    });
}

// Alerta de eliminación (confirmación con peligro)
function alertaEliminar(mensaje, titulo = '¿Eliminar registro?') {
    return Swal.fire({
        title: titulo,
        text: mensaje,
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
    });
}

// ============================================
// TOAST NOTIFICATIONS (notificaciones emergentes)
// ============================================

function mostrarToast(tipo, mensaje, posicion = 'top-end', tiempo = 3000) {
    const Toast = Swal.mixin({
        toast: true,
        position: posicion,
        showConfirmButton: false,
        timer: tiempo,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
    
    const iconos = {
        success: 'success',
        error: 'error',
        warning: 'warning',
        info: 'info'
    };
    
    Toast.fire({
        icon: iconos[tipo] || 'info',
        title: mensaje
    });
}

function toastExito(mensaje) {
    mostrarToast('success', mensaje);
}

function toastError(mensaje) {
    mostrarToast('error', mensaje);
}

function toastAdvertencia(mensaje) {
    mostrarToast('warning', mensaje);
}

function toastInfo(mensaje) {
    mostrarToast('info', mensaje);
}

console.log('✅ Alertas modernas cargadas correctamente');
