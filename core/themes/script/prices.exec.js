const prices_table_row = $("#data-default");
var prices_table = $("#main-table-body");

pagination = 50;

$.get( "./api/?prices&list", function( data ) {
    populate_data(JSON.parse(data), offset, prices_table, prices_table_row, 'prices'); 
    //$(".card-body").slideToggle();
});

$(".card-header").click(function(){
    $(".card-body").slideToggle();
    $("#apf_code").focus();
    //console.dir(this.parentElement.children[1]);
});

$("#add_prices_form").submit(function(e) {
    e.preventDefault(); // Avoid form to execute
    var form = $(this);
   
    // Execute only of validator is passed
        var form_data = new FormData(form[0]);
        
        $.ajax({
            url: './api/?prices&add',
            type: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function(msg){

                show_alert( "success", "Se ha agregado correctamente el listado" );

                $("#add_prices_form")[0].reset();

                // Reload the main table data
                $.get( "./api/?prices&list", function( data ) {
                    populate_data(JSON.parse(data), offset, prices_table, prices_table_row, 'prices'); 
                });
        }
    });
});

$("#data-check").click(function(){
    console.log(this);
});