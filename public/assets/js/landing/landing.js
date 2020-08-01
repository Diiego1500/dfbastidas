$('#register_button').click(function () {
    var Ruta = Routing.generate('register_user');
    var user_email = $('#user_email').val();
    var user_password = $('#user_password').val();
    Swal.fire({
        title: 'Espere',
        text:  'Estamos creando su usuario ',
        showConfirmButton: false,
        onBeforeOpen: function () {
            Swal.showLoading()
            return new Promise(function () {
                $.ajax({
                    type: 'POST',
                    url: Ruta,
                    data: ({email: user_email, 'password': user_password}),
                    async: true,
                    dataType: "json",
                    success: function (data) {
                        var success = data['success'];
                        if(success){
                            Swal.fire(
                                '¡Registrado exitosamente!',
                                'Ahora puedes iniciar sesión!',
                                'success'
                            )
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Ocurrió un error',
                            })
                        }
                    }
                });
            })
        }
    });
});
