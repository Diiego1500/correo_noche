$('.add_car').click(function () {
    var clase = event.target;
    product_id = clase.getAttribute('data-id');
    hash = clase.getAttribute('data-hash');
    Ruta = Routing.generate('add_producto_to_shopping_car');
    $.ajax({
        type: 'POST',
        url: Ruta,
        data: ({product_id:product_id, hash:hash}),
        async: true,
        dataType: "json",
        success: function (data) {
           console.log(data['response']);
        }
    });
});