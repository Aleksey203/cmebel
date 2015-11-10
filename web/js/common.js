/**
 * Created by Aleksey on 09.11.2015.
 */
$(document).ready(function(){
    var doc = $(this);

    doc.on('click', '.treeview a',function(){
        return false;
    });
});

//взять значение атрибута href
function get_val (str) {
    var arr = Array();
    arr = str.toString().split('#'.toString() );
    return arr[1];
}