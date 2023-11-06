document.addEventListener("DOMContentLoaded", function() {

    let btnIniciarSesion = document.getElementById("btn__iniciar-sesion");
    let btnRegistrarse = document.getElementById("btn__registrarse");

    if (btnIniciarSesion) btnIniciarSesion.addEventListener("click", iniciarSesion);
    if (btnRegistrarse) btnRegistrarse.addEventListener("click", register);

    function iniciarSesion() {
        // Aquí puedes agregar lógica adicional si la necesitas
    }

    function register() {
        // Aquí puedes agregar lógica adicional si la necesitas
    }
});
function toggleTooltip(event) {
    var tooltip = document.getElementById('passwordTooltip');
    var isVisible = tooltip.classList.contains('tooltip-visible');
    // Cerrar cualquier otro tooltip abierto
    closeAllTooltips();
    if (!isVisible) {
        tooltip.classList.add('tooltip-visible');
    }
    event.stopPropagation();
}

function closeAllTooltips() {
    var tooltips = document.getElementsByClassName('tooltip-content');
    for (var i = 0; i < tooltips.length; i++) {
        tooltips[i].classList.remove('tooltip-visible');
    }
}

// Cerrar los tooltips al hacer clic fuera de ellos
document.addEventListener('click', function(event) {
    var isClickInsideTooltip = event.target.classList.contains('tooltip-icon') || 
                               event.target.classList.contains('tooltip-content');
    if (!isClickInsideTooltip) {
        closeAllTooltips();
    }
});
