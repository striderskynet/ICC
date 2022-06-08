const prices_table_row = $("#data-default");
var prices_table = $("#main-table-body");

pagination = 50;

$.get( "./api/?prices&list", function( data ) {
    console.log(data);
    populate_data(JSON.parse(data), offset, prices_table, prices_table_row, 'prices'); 
    $(".card-body").slideToggle();
});

$(".card-header").click(function(){
    $(".card-body").slideToggle();
    $("#apf_code").focus();
    //console.dir(this.parentElement.children[1]);
});