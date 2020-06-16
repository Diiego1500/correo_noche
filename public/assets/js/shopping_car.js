$('.add_car').click(function () {
    var product = event.target;
    product_id = product.getAttribute('data-id');
    hash = product.getAttribute('data-hash');
    Ruta = Routing.generate('add_producto_to_shopping_car');
    Swal.fire({
        title: 'Agregar Al Carrito de compras',
        input: 'text',
        text: '¿Ingrese la cantidad?',
        showConfirmButton: true,
        preConfirm: (ammount) => {
            Swal.showLoading()
            return new Promise(function () {
                $.ajax({
                    type: 'POST',
                    url: Ruta,
                    data: ({product_id: product_id, hash: hash, ammount:ammount }),
                    async: true,
                    dataType: "json",
                    success: function (data) {
                        $('#not_added').hide();
                        var product_order = addProduct(data['product_name'], data['product_price'], data['product_ammount']);
                        $('#shopping_car').append(product_order);
                        Swal.fire(
                            '¡Agregado Exitosamente!',
                            '¡El producto se ha agregado exitosamente al carrito de compras!',
                            'success'
                        )
                    }
                });
            })

        }
    });
});

function addProduct(product_name, product_price, product_ammount) {
    var product_order =
        "<div class='p-2'>" +
        "<div class='p-1 rounded border'><h5>" + product_name + "</h5>" +
        "<div><small>Precio Unidad: $" + product_price + "</small></div>" +
        "<div class='row'>" +
        "<div class='col-md-6'><small>Cantidad: " + product_ammount + "</small></div>" +
        "<div class='col-md-6 text-right'><small><strong>SubTotal " + (product_price * product_ammount) + "</strong></small></div>" +
        "</div>" +
        "</div>";
    return product_order;
};
