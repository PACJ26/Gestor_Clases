function confirmarEliminacion(id) {
    iziToast.question({
        timeout: 20000,
        close: false,
        overlay: true,
        displayMode: 'once',
        title: 'Confirmación',
        message: '¿Estás seguro de que deseas eliminar este usuario?',
        position: 'center',
        buttons: [
            ['<button><b>SI</b></button>', function (instance, toast) {
                window.location.href = '../Controladores/eliminar_usuario.php?id=' + id;
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
            }, true],
            ['<button>NO</button>', function (instance, toast) {
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
            }]
        ]
    });
}