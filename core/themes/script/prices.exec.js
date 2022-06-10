const prices_table_row = $("#data-default");
var prices_table = $("#main-table-body");
var selected_items = [];

const main_table_row = $("#data-default");
var main_table = $("#main-table-body");

pagination = 25;

$.get( "./api/?prices&list&orderBy=code", function( data ) {
    populate_data(JSON.parse(data), offset, prices_table, prices_table_row, 'prices'); 
    editable_table_reload();
});

// Execute every time there is a search in the search bar
$("#main_search,#main_search_button").prop("disabled", true);
$("#main_search,#main_search_button").prop(
  "title",
  "Deshabilitada la busqueda hasta nueva version"
);

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
                    editable_table_reload();
                });
        }
    });
});

function select_tr(element, no){
    //delete selected_items[selected_items.indexOf(element.id)];
    
    id = element.id.replace("data_p", "");
    //element.classlist.add("bg-primary");
    //console.log($("#data-check-" + no));
    if ($("#" + element.id).hasClass('bg-info text-white')) {
        $("#" + element.id).removeClass( 'bg-info text-white');
        $("#data-check-" + no).prop( "checked", false );
        delete selected_items.splice(selected_items.indexOf(no),1);
    } else {
        $("#" + element.id).addClass( 'bg-info text-white');
        $("#data-check-" + no).prop( "checked", true );
        selected_items.push(no);
    }

    var len = selected_items.length;
    $("#delete_price_button span strong").html(len);
    $("#duplicate_price_button span strong").html(len);

    if ( len > 0 ) {
        $("#delete_price_button").fadeIn()
        $("#duplicate_price_button").fadeIn()
    } else {
        $("#delete_price_button").fadeOut()
        $("#duplicate_price_button").fadeOut()
    }
};

$("#delete_price_button").click(function(e){
    e.preventDefault();

    if ( selected_items.length > 0){
        console.log(selected_items);
        console.log("Deleting some items");

        $.ajax({
            url: './api/?prices&delete',
            type: 'POST',
            data: {info: selected_items},
            success: function(msg){
                $.get( "./api/?prices&list", function( data ) {
                    populate_data(JSON.parse(data), offset, prices_table, prices_table_row, 'prices'); 
                    editable_table_reload()
                    clear_selected();
                });
        }
        });
    }

    clear_selected();
});

function clear_selected(){

    selected_items = [];
    var len = selected_items.length;
    $("#delete_price_button span strong").html(len);
    $("#duplicate_price_button span strong").html(len);


    $("#delete_price_button").toggle();
    $("#duplicate_price_button").toggle();
}

$("#duplicate_price_button").click(function(e){
    e.preventDefault();

    if ( selected_items.length > 0){
        console.log("Duplicating items");

        $.ajax({
            url: './api/?prices&duplicate',
            type: 'POST',
            data: {info: selected_items},
            success: function(msg){
                $.get( "./api/?prices&list", function( data ) {
                    populate_data(JSON.parse(data), offset, prices_table, prices_table_row, 'prices'); 
                    editable_table_reload()
                    clear_selected();
                });
        }
        });
    }
});

$("#apf_clear_button").click(function(){
    $("#add_prices_form")[0].reset();
    $("#apf_code").focus();
});
/*
    var timeOut;
$('.editable').on('mousedown touchstart', function(e) {
    console.log(e);
    timeOut = setInterval(function(){
        $(e.currentTarget.children[0]).toggle();
        $(e.currentTarget.children[1]).toggle();
    }, 500);
  }).bind('mouseup mouseleave touchend', function() {
    clearInterval(timeOut);
  }).keydown(function(event) {
   
     var id = event.key || event.which || event.keyCode || 0;  
     console.log(event)
        if (id === "Enter") {
            $(event.currentTarget.children[0]).text($(event.target).val());
            $(event.currentTarget.children[0]).toggle();
            $(event.target).toggle();
        }
  });
*/
function editable_table_reload(){
    $( ".editable" ).dblclick(function(e) {
        $(e.currentTarget.children[0]).toggle();
        $(e.currentTarget.children[1]).toggle();
      });
      
    $( ".editable" ).keydown(function(event) {
        var id = event.key || event.which || event.keyCode || 0;  
            if (id === "Enter") {
                $(event.currentTarget.children[0]).text($(event.target).val());
                $(event.currentTarget.children[0]).parent().addClass("bg-warning text-white font-weight-bold");
                $(event.currentTarget.children[0]).toggle();
                $(event.target).toggle();

                var query = `UPDATE \`price_list\` SET \`${event.currentTarget.dataset.type}\`='${$(event.target).val()}' WHERE \`id\`='${event.currentTarget.parentElement.dataset.priceId}'`;
                
                $.get( "./api/?query&query=" + query, function( data ) {
                    //console.log(data);
                });
              
            }

            if (id === "Escape") {
                $(event.currentTarget.children[0]).toggle();
                $(event.target).toggle();
            }
    });

    $('.editable input').hide()
}
