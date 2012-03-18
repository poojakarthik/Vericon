<?PHP include "../auth/iprestrict.php"; ?>

<?PHP
function valData($d){
  if(strlen($d[1])!=26){
    echo $d[0],"\t",$d[1],"\t",$d[2],"\tBad Row\n";  
    return false;
  }

  return true;
}

function loadData(){

  if($_FILES["file"]["err"] > 0) return 'FILE';
  if($_FILES["file"]["type"] != "text/csv") return 'HEAD';


  $f = fopen($_FILES["file"]["tmp_name"], 'r') or die() ;
  if(!$f) return 'FILE';

  //Checks first line of file aganst hash of correct header row
  if(md5(fgets($f)) != '5dbbeec481915087920bae60e8d6279f') return 'HEAD'; 

  echo "<pre>";
  $i=0;
  while($d = fgetcsv($f,0,',')){
    if(valData($d)){
      //INSERT Query Here
    }
    $i++;
  }
  echo "</pre>";
  return 'OK';
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Leads</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?PHP include "../source/jquery.php"; ?>
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/leads_menu.php";
?>

<div id="text">
<?PHP
if ($_POST){
  $err = loadData();
}
?>

<?php if ($err == 'FILE'): ?>
<h1>An error occurred uploading your file!</h1>
<?php endif; ?>

<?php if ($err == 'HEAD'): ?>
<h1>Incorrect File Format!</h1>
<?php endif; ?>

<?php if ($err == 'OK'): ?>
<h1>Done!</h1>
<?php endif; ?>

<?php if (!$_POST || $err != 'OK'): ?>
<form action="" method="post" enctype="multipart/form-data">
<input name="file" type="file" id="file" size="30"/>
<input name="Submit" type="submit" id="submit" />
</form>
<?php endif; ?>

</div>
</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>

