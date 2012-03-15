<script type="text/javascript">
var prelink = "../script/script_tpv.php?campaign=<? echo $_GET['campaign']; ?>&plan=<? echo $_GET['plan']; ?>&alias=<? echo $_GET['alias']; ?>&id=<? echo $_GET['id']; ?>&page=";
var page = "<? echo $_GET['page']; ?>";

function enableIt(){
	if (document.getElementById('Btn_Next') != null)
	{
		document.getElementById('Btn_Next').style.display = 'inline';
	}
	if (document.getElementById('Btn_Back') != null)
	{
		document.getElementById('Btn_Back').style.display = 'inline';
	}
}

function Back()
{
	page--;
	document.getElementById('image_load').style.display = 'inline';
	if (document.getElementById('Btn_Next') != null)
	{
		document.getElementById('Btn_Next').style.display = 'none';
	}
	document.getElementById('Btn_Back').style.display = 'none';
	document.getElementById('Btn_Cancel').style.display = 'none';
	var link = prelink+page;

	window.location = link;
}

function N()
{
	page++;
	document.getElementById('image_load').style.display = 'inline';
	document.getElementById('Btn_Next').style.display = 'none';
	document.getElementById('Btn_Cancel').style.display = 'none';
	if (document.getElementById('Btn_Back') != null)
	{
		document.getElementById('Btn_Back').style.display = 'none';
	}
	var link = prelink+page;

	window.location = link;
}

function Next(id,action)
{
	if (action == "bus_info")
	{
		var abn = $( "#abn" ),
			abn_status = $( ".abn_status" ),
			position = $( "#position" );
			
		$.get("source/submit.php", { id: id, action: action, abn: abn.val(), abn_status: abn_status.html(), position: position.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					parent.Submit_Error(data);
				}
			});
	}
	else if (action == "name")
	{
		var title = $( "#title" ),
			first = $( "#first" ),
			middle = $( "#middle" ),
			last = $( "#last" );
			
		$.get("source/submit.php", { id: id, action: action, title: title.val(), first: first.val(), middle: middle.val(), last: last.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					parent.Submit_Error(data);
				}
			});
	}
	else if (action == "dob")
	{
		var dob = $( "#datepicker" );
			
		$.get("source/submit.php", { id: id, action: action, dob: dob.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					parent.Submit_Error(data);
				}
			});
	}
	else if (action == "id_info")
	{
		var id_type = $( "#id_type" ),
			id_num = $( "#id_num" );
			
		$.get("source/submit.php", { id: id, action: action, id_type: id_type.val(), id_num: id_num.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					parent.Submit_Error(data);
				}
			});
	}
	else if (action == "physical")
	{
		var physical = $( "#physical" );
			
		$.get("source/submit.php", { id: id, action: action, physical: physical.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					parent.Submit_Error(data);
				}
			});
	}
	else if (action == "postal")
	{
		var postal = $( "#postal" );
			
		$.get("source/submit.php", { id: id, action: action, postal: postal.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					parent.Submit_Error(data);
				}
			});
	}
	else if (action == "mobile")
	{
		var mobile = $( "#mobile" );
			
		$.get("source/submit.php", { id: id, action: action, mobile: mobile.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					parent.Submit_Error(data);
				}
			});
	}
	else if (action == "email")
	{
		var email = $( "#email" ),
			billing = $('input[name=billing]:checked'),
			welcome = $('input[name=welcome]:checked');
			
		$.get("source/submit.php", { id: id, action: action, email: email.val(), billing: billing.val(), welcome: welcome.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					parent.Submit_Error(data);
				}
			});
	}
	else if (action == "email2")
	{
		var email = $( "#email2" ),
			billing = "email",
			welcome = "email";
			
		$.get("source/submit.php", { id: id, action: "email", email: email.val(), billing: billing, welcome: welcome },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					parent.Submit_Error(data);
				}
			});
	}
	else
	{
		N();
	}
}
</script>