$(document).ready(function () {
  q = 1;
  $("[data-bss-chart]").each(function (index, elem) {
    $.get("./chart" + q + ".json", function (data) {
      console.log(data);
      this.chart = new Chart($(elem), data);
    });
    q++;
    /*
		console.dir($(elem).data('bss-chart'))
		this.chart = new Chart($(elem), $(elem).data('bss-chart'));*/
  });
});
