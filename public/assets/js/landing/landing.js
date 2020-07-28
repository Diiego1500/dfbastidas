Swal.fire(
    'Good job!',
    'You clicked the button!',
    'success'
)

var Ruta = Routing.generate('register_user');

var id = 1;
$.ajax({
    type: 'POST',
    url: Ruta,
    data: ({id: id}),
    async: true,
    dataType: "json",
    success: function (data) {
        console.log(data['success']);
    }
})
;