$('#register_button').click(function () {
    var Ruta = Routing.generate('register_user');
    var user_email = $('#user_email').val();
    var user_password = $('#user_password').val();
    var user_name = $('#user_name').val();
    var user_last_name = $('#user_last_name').val();
    var user_birthdate = $('#user_birthdate').val();
    var data = {email: user_email, password: user_password, user_name:user_name, user_last_name:user_last_name, user_birthdate:user_birthdate}
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
                    data: (data),
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
