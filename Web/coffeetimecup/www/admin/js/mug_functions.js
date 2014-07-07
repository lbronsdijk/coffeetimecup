initWebsocket();

setInterval(function(){
    $.get( "http://local.coffeetimecup/admin/inc/scripts/get_degree.php?mug=<?php echo $default_mug_rows->mug_serial; ?>&user=<?php echo $GLOBAL_user_id; ?>", function( data ){
        $( ".result" ).html( data );

        if(data < 15){
            var status = " is cold!";
        }

        if(data > 30){
            var status = " is getting cold!";
        }

        if(data > 50){
            var status = " is warm!";
        }

        if(data > 80){
            var status = " is hot!";
        }

        document.getElementById("current_mug_degree").innerHTML = data;
        document.getElementById("mug_status").innerHTML = "Your <?php echo $default_mug_rows->mug_name; ?> " + status;
    });
}, 10 * 1000);

function initWebsocket(){

    ws = new WebSocket("ws://localhost:8090");

    ws.onopen = function() {
        ws.send("Server start");
    };

    ws.onclose = function() {};

    ws.onmessage = function(evt) {
        console.log(evt.data);
        $("#current_mug_degree").append(
            $('#current_mug_degree').text(evt.data)
        )
        

        ws.send("Next value");
    }
}

function default_mug(mug_serial){

    $.post("http://local.coffeetimecup/admin/inc/scripts/default_mug.php",{
        serial:mug_serial,
        userid:"<?php echo $GLOBAL_user_id; ?>"
    });

    document.getElementById("default_status").innerHTML = "You've changed your default mug to " + mug_serial + " <a href='#' onclick='refreshpage()'>Refresh</a> the page to update the status.";
}

function refreshpage(){
    location.reload();
}

$( function() {
    $('.txtBoxValue').click (function() {
        var serial = this.id;
        $(this).hide();
        $('#' + serial + '_txt').show();
    });

    $('.txtBox').on('blur', function() {
        var textbox_id = this.id;
        var serial = textbox_id.replace("_txt", "");

        var that = $(this);
        $('#' + serial).text(that.val()).show();

        $.post("http://local.coffeetimecup/admin/inc/scripts/post_mugname.php",
            {
                serial:serial,
                userid:"<?php echo $GLOBAL_user_id; ?>",
                name:that.val()
            });

        that.hide();
    });

});