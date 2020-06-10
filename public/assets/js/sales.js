$('.change_ammount').click(function () {
    var product = event.target;
    product_id = product.getAttribute('data-id');
    alert(product_id)
});