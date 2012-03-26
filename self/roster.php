<?php
include "../auth/iprestrict.php";
include "../js/self-js.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Self Verification :: Roster</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<style>
.availability
{
	background-image:url('../images/availability_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-top:5px;
	margin-left:10px;
}

.availability:hover
{
	background-image:url('../images/availability_btn_hover.png');
	cursor:pointer;
}

div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .4em 10px; }
</style>
<script>
var na = new Array();
na[1] = 0;
na[2] = 0;
na[3] = 0;
na[4] = 0;
na[5] = 0;
</script>
<script> //availability
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var user = "<?php echo $ac["user"]; ?>",
		centre = "<?php echo $ac["centre"]; ?>";
		start_hour1 = $( "#start_hour1" ),
		start_minute1 = $( "#start_minute1" ),
		end_hour1 = $( "#end_hour1" ),
		end_minute1 = $( "#end_minute1" ),
		start_hour2 = $( "#start_hour2" ),
		start_minute2 = $( "#start_minute2" ),
		end_hour2 = $( "#end_hour2" ),
		end_minute2 = $( "#end_minute2" ),
		start_hour3 = $( "#start_hour3" ),
		start_minute3 = $( "#start_minute3" ),
		end_hour3 = $( "#end_hour3" ),
		end_minute3 = $( "#end_minute3" ),
		start_hour4 = $( "#start_hour4" ),
		start_minute4 = $( "#start_minute4" ),
		end_hour4 = $( "#end_hour4" ),
		end_minute4 = $( "#end_minute4" ),
		start_hour5 = $( "#start_hour5" ),
		start_minute5 = $( "#start_minute5" ),
		end_hour5 = $( "#end_hour5" ),
		end_minute5 = $( "#end_minute5" ),
		tips = $( ".error" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 300,
		width: 500,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Submit Availability": function() {
				$.get("availability.php", { user: user, centre: centre, 
				start_hour1: start_hour1.val(), start_minute1: start_minute1.val(), end_hour1: end_hour1.val(), end_minute1: end_minute1.val(), na1: na[1], start_hour2: start_hour2.val(), start_minute2: start_minute2.val(), end_hour2: end_hour2.val(), end_minute2: end_minute2.val(), na2: na[2], start_hour3: start_hour3.val(), start_minute3: start_minute3.val(), end_hour3: end_hour3.val(), end_minute3: end_minute3.val(), na3: na[3], start_hour4: start_hour4.val(), start_minute4: start_minute4.val(), end_hour4: end_hour4.val(), end_minute4: end_minute4.val(), na4: na[4], start_hour5: start_hour5.val(), start_minute5: start_minute5.val(), end_hour5: end_hour5.val(), end_minute5: end_minute5.val(), na5: na[5] },
				function(data) {
					if (data == "submitted")
					{
						$( "#dialog-form" ).dialog( "close" );
						location.reload();
					}
					else
					{
						updateTips(data);
					}
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function Availability()
{
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script>
function NA(day)
{
	var check = "#not_available" + day,
		start_hour = "#start_hour" + day,
		start_minute = "#start_minute" + day,
		end_hour = "#end_hour" + day,
		end_minute = "#end_minute" + day;
	
	if ($( check ).attr('checked'))
	{
		$( start_hour ).attr("disabled", true);
		$( start_minute ).attr("disabled", true);
		$( end_hour ).attr("disabled", true);
		$( end_minute ).attr("disabled", true);
		na[day] = 1;
	}
	else
	{
		$( start_hour ).removeAttr("disabled");
		$( start_minute ).removeAttr("disabled");
		$( end_hour ).removeAttr("disabled");
		$( end_minute ).removeAttr("disabled");
		na[day] = 0;
	}
}
</script>
</head>

<body>
<div style="display:none;">
<img src="../images/availability_btn_hover.png" />
</div>
<div id="main_wrapper">
<?php
include "../source/header.php";
include "../source/self_menu.php";
?>

<div id="text" class="demo">
<p>Down until further notice</p>

</div>

</div>

<?php
include "../source/footer.php";
?>

</body>
</html>