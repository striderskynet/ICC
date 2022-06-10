var clientModalShow = true;
var add_voucher_modal = new bootstrap.Modal(document.getElementById('add_voucher_modal'));
var clientModalLabel = new bootstrap.Modal(document.getElementById('clientModal'));
var clientModalBody = document.getElementById('clientModalBody');

const voucher_default_row = $("#data-default");
var voucher_main_table = $("#main-table-body");

// Populate the main Table on Load
$.get("./api/?vouchers&list", function (data) {
  populate_data(
    JSON.parse(data),
    1,
    voucher_main_table,
    voucher_default_row,
    "voucher"
  );
});

// Execute when "ADD Voucher" button is clicked
$("#button_voucher_add").click(function () {
  console.log("Mostrando modal de Reserva Nueva");

  // Show "ADD Voucher" modal
  add_voucher_modal.show();
});

// Execute every time there is a search in the search bar
$("#main_search,#main_search_button").prop("disabled", true);
$("#main_search,#main_search_button").prop(
  "title",
  "Deshabilitada la busqueda hasta nueva version"
);


// Execute when "DEL Voucher" button is pressed
function button_voucher_del(button) {
  //$("#button_user_del").click(function(){
  clientModalShow = false;

  // Getting voucherID from DataSet
  var voucher_id = button.dataset.voucherId
  let url_del_voucher = "./api/?vouchers&delete&id=" + voucher_id;

  // Executing the API for VOUCHER DELETION
  $.get(url_del_voucher, function (data) {
    show_alert("danger", `Se ha eliminado el Voucher con ID: ${voucher_id}`, 5);

    // Populate the main Table
    $.get("./api/?vouchers&list", function (data) {
      populate_data(
        JSON.parse(data),
        1,
        voucher_main_table,
        voucher_default_row,
        "voucher"
      );
    });
  });

  // Wait TIME for reloading
  setTimeout(function () {
    clientModalShow = true;
  }, 200);
}

function button_voucher_print(element) {
  voucher_id = element.dataset.voucherId;
  show_alert("primary", `Imprimiendo voucher ID ${voucher_id}`, 5);

  window.open("./api/voucher.php?id=" + voucher_id, "_blank").focus();
}


$('.priceAutoComplete').autoComplete({
  resolver: 'custom',
  minLength:1,
  events: {
      search: function (qry, callback) {
          // let's do a custom ajax call
          $.ajax(
              './api/?prices&list_min',
              {
                  data: { 'q': qry}
              }
          ).done(function (res) {
              callback(JSON.parse(res))
          });
      }
  }
});

$('.priceAutoComplete').on('autocomplete.select', function (evt, item) {
  const element = this;
  const pr_text = $("#avf_data");
  const in_date = $("#avf_inDate");
  const out_date = $("#avf_outDate");

  $.get( `./api/?prices&list&wh=WHERE+id+=+${item.value}`, function( data ) {
    data = JSON.parse(data);
    element.value = data[0].code;

    console.dir(data);
    pr_text.text(`${data[0].name} (${data[0].place})\n\t${data[0].type}`);
    in_date.prop("min", data[0].from_date );
    in_date.prop("max", data[0].to_date );
    out_date.prop("min", data[0].from_date );
    out_date.prop("max", data[0].to_date );
});
  
  //select_name("#" + this.id, item.text, item.value);
});   