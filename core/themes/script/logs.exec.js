function load_logs(log_file)
{
    $.ajax({
                url: "core/themes/logs.php?log=" + log_file,
                cache: false
            })
                .done(function( result ) {
                    $("#log_wrapper").html(result);
            });

    console.log ( "Loading logs file: " + log_file);
}

load_logs(document.getElementById('logs_select').value);