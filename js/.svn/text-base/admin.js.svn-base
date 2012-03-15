function LoadScript()
{
	if(document.getElementById("campaign").value == "--- Campaign ---")
	{
		window.alert('Select a Campaign!')
	}
	else if(document.getElementById("plan").value == "--- Plan ---" || document.getElementById("plan").value == "--- Landline ---" || document.getElementById("plan").value == "--- Internet ---" || document.getElementById("plan").value == "--- Bundle ---")
	{
		window.alert('Select a Plan!')
	}
	else
	{
		var campaign = document.getElementById("campaign").options[document.getElementById("campaign").selectedIndex].value;
		var plan = document.getElementById("plan").options[document.getElementById("plan").selectedIndex].text;
		var l = "export.php?campaign=" + campaign + "&plan=" + plan;
		window.open(l,'','menubar=no,scrollbars=yes');
	}
}