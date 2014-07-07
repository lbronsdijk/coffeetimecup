<script src="js/jquery.minicolors.js"></script>
<link rel="stylesheet" href="stylesheet/jquery.minicolors.css">

<script>
		$(document).ready( function() {
			
            $('.colorpicker').each( function() {
				$(this).minicolors({
					control: $(this).attr('data-control') || 'hue',
					defaultValue: $(this).attr('data-defaultValue') || '',
					inline: $(this).attr('data-inline') === 'true',
					letterCase: $(this).attr('data-letterCase') || 'lowercase',
					opacity: $(this).attr('data-opacity'),
					position: $(this).attr('data-position') || 'bottom left',
					change: function(hex, opacity) {
						var log;
						try {
							log = hex ? hex : 'transparent';
							if( opacity ) log += ', ' + opacity;
							console.log(log);
						} catch(e) {}
					},
					theme: 'default'
				});
                
            });
			
		});
</script>
	
<?php
    if(isset($_POST["add_mug"])){
        $mug_serial = escape_string($_POST["mug_serial"]);
        add_mug($mug_serial);
    }

    $default_mug_query = $mysqli->query("SELECT * FROM `mugs` WHERE `user_id` = '" . $GLOBAL_user_id . "' AND `default` = '1'");
    $default_mug_count = $default_mug_query->num_rows;

    if($default_mug_count == 1){

        $default_mug_rows = $default_mug_query->fetch_object();
        ?>
        <script>

            setInterval(function(){
                $.get( "http://local.coffeetimecup/admin/inc/scripts/get_degree.php?mug=<?php echo $default_mug_rows->mug_serial; ?>&user=<?php echo $GLOBAL_user_id; ?>", function( data ){
                    $( ".result" ).html( data );

                    if(data >= 0){
                        var status = " is cold!";
                    }

                    if(data > 30){
                        var status = " is getting cold!";
                    }

                    if(data > 38){
                        var status = " is warm!";
                    }

                    if(data > 45){
                        var status = " is hot!";
                    }

                    document.getElementById("current_mug_degree").innerHTML = data;
                    document.getElementById("mug_status").innerHTML = "Your <?php echo $default_mug_rows->mug_name; ?> " + status;
                });
            }, 3 * 1000);		
			
			$( document ).ready(function() {

                initWebsocket();

				$( ".mug_color" ).css( "background-color", "<?php echo $default_mug_rows->mug_color; ?>" );
			});

            function initWebsocket(){

                ws = new WebSocket("ws://localhost:8090");

                ws.onopen = function() {
                    ws.send("Server start");
                };

                ws.onclose = function() {};

                ws.onmessage = function(evt) {
                    //console.log(evt.data);

                    // $("#current_mug_degree").append(
                    //     $('#current_mug_degree').text(evt.data)
                    // )
                    // $("#current_mug_degree_small").append(
                    //     $('#current_mug_degree_small').text(evt.data)
                    // )

                    var data = evt.data;

                    console.log(data);

                    $.ajax({
                        url: "http://local.coffeetimecup/admin/inc/scripts/write_txt.php",
                        type: "POST",
                        data: { 'temp': '' + data +'' }
                    });
                    
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

				$('.colorpicker').on('change', function(){
					var serial = this.id.replace("_color", "");
					var color = $('#' + serial + '_color').val();
				    
				    $.post("http://local.coffeetimecup/admin/inc/scripts/change_color.php",
                        {
                            serial:serial,
                            userid:"<?php echo $GLOBAL_user_id; ?>",
                            color:color
                        });
                        
                    if ($('#' + serial + '_default').is(':checked')) {
					    $( ".mug_color" ).css( "background-color", color );
					}
				});
            });
        </script>
        <?php
    }

	echo "Welcome " . $GLOBAL_user_firstname . " ";
?>
<div class="logout">
    <a href="?logout">Uitloggen</a>
</div>

<div class="default_mug">
    <div class="mug_color">
        <div class="mug_content">
            <div class="degree">°C</div>
            <div id="current_mug_degree"><?php if($default_mug_count == 1){ get_degree($default_mug_rows->mug_serial, $GLOBAL_user_id); }else{ echo "0"; } ?></div>
        </div>
    </div>

    <div class="status">
        <div id="mug_status"><?php if($default_mug_count == 1){ echo "Loading status..."; } ?></div>
        <div id="default_status"></div>
    </div>
</div>
<div class="divider"></div>

<div class="add_mug">
	Add a mug (Serial)<br>
	<form action="" method="post">
		<input type="text" name="mug_serial"><input type="submit" name="add_mug" value="Add">
	</form>
</div>
<?php

	

	
?>

<table border="1" class="mugs">
	<tr>
		<th>Mug Name</th>
		<th>Mug degree</th>
		<th>Mug Color</th>
		<th>Default</th>
	</tr>
	<?php
		$mugs_query = $mysqli->query("SELECT * FROM mugs WHERE user_id = '" . $GLOBAL_user_id . "' ORDER BY `id` ASC");

		while($mug_rows = $mugs_query->fetch_object()){
			echo "<tr>";
			echo "<td width=500><input class='txtBox' id='" . $mug_rows->mug_serial . "_txt' type='textbox' value='" . $mug_rows->mug_name . "' style='display:none;' /><span class='txtBoxValue' id='" . $mug_rows->mug_serial . "'>" . $mug_rows->mug_name . "</span></td>";
			echo "<td id=\"current_mug_degree_small\">";
            if($default_mug_count == 1){ get_degree($default_mug_rows->mug_serial, $GLOBAL_user_id); }else{ echo "0"; }
			echo "</td>";
			echo "<td><input type='hidden' id='" . $mug_rows->mug_serial . "_color' class='colorpicker' value='" . $mug_rows->mug_color . "'></td>";
			echo "<td>";
			if($mug_rows->default == 1){
				echo "<input type='radio' name='default_mug' id='" . $mug_rows->mug_serial . "_default' value='" . $mug_rows->mug_serial . "' checked='checked'>";
			}else{
				echo "<input type='radio' name='default_mug' id='" . $mug_rows->mug_serial . "_default' value='" . $mug_rows->mug_serial . "' onclick='default_mug(this.value)'>";
			}
			echo "</td>";
			echo "</tr>";
		}
	?>

</table>