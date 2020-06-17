$('.change_ammount').click(function () {
    var product = event.target;
    product_order_id = product.getAttribute('data-id');
    var ammount = $('#ammount_'+product_order_id).val();
    if(isNaN(ammount)){
        incorrect_ammount();
    }
    else{
        if(ammount <= 0){
            incorrect_ammount();
        }else{
            var Route = Routing.generate('change_ammount',{id:product_order_id,ammount:ammount});
            window.location.href = Route;
        }
    }
});

function incorrect_ammount() {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Por favor introduzca un valor válido'
    })
}

$('#order_paymentMethod').change(function () {
    var selected = this.value;
    if(selected == 'Efectivo' || selected == 'Datafono'){
        $('#message').show();
        $('#send').show();
    }else{
        $('#message').hide();
        $('#send').hide();
    }

})