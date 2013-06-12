<script type="text/javascript">
window.onload=function(){
	setTimeout(enableIt,4000)
}

var prelink = "../script/script.php?t=<? echo $_GET['t']; ?>&campaign=<? echo $_GET['campaign']; ?>&plan=<? echo $_GET['plan']; ?>&page=";
var page = "<? echo $_GET['page']; ?>";

function enableIt(){
	if (document.getElementById('Btn_Next') != null)
	{
		document.getElementById('Btn_Next').style.display = 'block';
	}
	if (document.getElementById('Btn_Back') != null)
	{
		document.getElementById('Btn_Back').style.display = 'block';
	}
}

function Next()
{
	page++;
	document.getElementById('Btn_Next').style.display = 'none';
	if (document.getElementById('Btn_Back') != null)
	{
		document.getElementById('Btn_Back').style.display = 'none';
	}
	var link = prelink+page;

	window.location = link;
}

function Back()
{
	page--;
	if (document.getElementById('Btn_Next') != null)
	{
		document.getElementById('Btn_Next').style.display = 'none';
	}
	document.getElementById('Btn_Back').style.display = 'none';
	var link = prelink+page;

	window.location = link;
}

function LoadScript()
{
	if(document.getElementById("campaign").value == "--- Campaign ---")
	{
		$( "#dialog-confirm" ).dialog( "open" );
	}
	else if(document.getElementById("plan").value == "--- Plan ---" || document.getElementById("plan").value == "--- Landline ---" || document.getElementById("plan").value == "--- Internet ---" || document.getElementById("plan").value == "--- Bundle ---")
	{
		$( "#dialog-confirm2" ).dialog( "open" );
	}
	else
	{
		var t = document.getElementById("t").value;
		var campaign = document.getElementById("campaign").options[document.getElementById("campaign").selectedIndex].value;
		var plan = document.getElementById("plan").options[document.getElementById("plan").selectedIndex].text;
		var l = "../script/script.php?t=" + t + "&campaign=" + campaign + "&plan=" + plan + "&page=1";
		document.getElementById("script").src = l;
	}
}
</script>