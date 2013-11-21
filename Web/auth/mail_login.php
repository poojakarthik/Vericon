<?php
include "../source/browser.php";

if ($browser["name"] == "VeriCon")
{
?>
<script>
window.location = 'https://mail.vericon.com.au/auth/mail_login.php';
</script>
<?php
}