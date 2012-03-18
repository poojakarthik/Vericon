<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Customer Care :: Useful Info</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<style>
	div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
	div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>
<script>
$(function() {
	$( "#tabs" ).tabs();
});
</script>
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/cct_menu.php";
?>

<div id="text" class="demo">

<p><img src="../images/transfer_instructions_header.png" width="230" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<p>1. Click on <b><u>LINE 2</u></b> and dial the extension number from your eyebeam</p>
<p>2. Click on <b><u>LINE 1</u></b> and explain to the customer that you will be transferring them to "_______" from the other department</p>
<p>3. Click on <b><u>XFER</u></b> and then <b><u>LINE 2</u></b> which will transfer the customer to the other person</p>
<br />

<p><img src="../images/transfer_extensions_header.png" width="215" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<div id="tabs">
	<ul>
		<li><a href="#billing">Billing</a></li>
        <li><a href="#collections">Collections</a></li>
		<li><a href="#prov-data">Provisioning - Data</a></li>
		<li><a href="#prov-voice">Provisioning - Voice</a></li>
	</ul>
	<div id="prov-voice">
		<div id="users-contain" class="ui-widget">
            <table id="users" class="ui-widget ui-widget-content">
                <thead>
                    <tr class="ui-widget-header ">
                        <th>Name</th>
                        <th>Extension</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>General</td>
                        <td>331</td>
                    </tr>
                    <tr>
                        <td>Alina</td>
                        <td>5201</td>
                    </tr>
                    <tr>
                        <td>Beau</td>
                        <td>5203</td>
                    </tr>
                    <tr>
                        <td>Varun</td>
                        <td>5205</td>
                    </tr>
                </tbody>
            </table>
        </div>
	</div>
    <div id="prov-data">
		<div id="users-contain" class="ui-widget">
            <table id="users" class="ui-widget ui-widget-content">
                <thead>
                    <tr class="ui-widget-header ">
                        <th>Name</th>
                        <th>Extension</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>General</td>
                        <td>332</td>
                    </tr>
                    <tr>
                        <td>Wes</td>
                        <td>5210</td>
                    </tr>
                    <tr>
                        <td>Justin</td>
                        <td>5213</td>
                    </tr>
                    <tr>
                        <td>Johnny</td>
                        <td>5214</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="billing">
		<div id="users-contain" class="ui-widget">
            <table id="users" class="ui-widget ui-widget-content">
                <thead>
                    <tr class="ui-widget-header ">
                        <th>Name</th>
                        <th>Extension</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>General</td>
                        <td>303</td>
                    </tr>
                    <tr>
                        <td>Nandani</td>
                        <td>5305</td>
                    </tr>
                    <tr>
                        <td>Hamsa</td>
                        <td>5302</td>
                    </tr>
                    <tr>
                        <td>Sam</td>
                        <td>5303</td>
                    </tr>
                    <tr>
                        <td>Tim</td>
                        <td>5304</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="collections">
		<div id="users-contain" class="ui-widget">
            <table id="users" class="ui-widget ui-widget-content">
                <thead>
                    <tr class="ui-widget-header ">
                        <th>Name</th>
                        <th>Extension</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>General</td>
                        <td>9305</td>
                    </tr>
                    <tr>
                        <td>Aaron</td>
                        <td>2055</td>
                    </tr>
                    <tr>
                        <td>Stacey</td>
                        <td>130 OR 2056</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>