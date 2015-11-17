/**
 * Created by Aleksey on 09.11.2015.
 */
$(document).ready(function(){
    var doc = $(this);

    doc.on('click', 'a.remove_product',function(){
        $(this).parents('.product').remove();
        return false;
    });

    doc.on('click', '.treeview a',function(){
        return false;
    });

    doc.on('change', '.product-quantity',function(){
        var quantity = parseInt($(this).val());
        var tr = $(this).parents('tr.product');
        var price = parseInt(tr.find('.price').html());
        var cost = price*quantity;
        tr.find('.product-cost').html(cost);
    });


    doc.on('click', '#saveOrderProduct',function(){

    var urlAddProduct = '/index.php?r=orders/addproduct',
        order_id = parseInt($('#add_product_box').attr('data-order-id')),
        product_id = parseInt($('select[name="product_id"]').val()),
        product_quantity = parseInt($('select[name="product_quantity"]').val());
    $.ajax({
        url: urlAddProduct,
        type: 'GET',
        dataType: 'json',
        data: {
            product_id: product_id,
            product_quantity: product_quantity,
            order_id: order_id,
        },
        success: function(data) {
            if (data.success) {
                var tbody = $('.order_products tbody');
                tbody.append(data.html);
                $('#add-product-modal').modal('hide')
            } else {
                alert(data.data);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
    return false;
    });
});

//href
function get_val (str) {
    var arr = Array();
    arr = str.toString().split('#'.toString() );
    return arr[1];
}