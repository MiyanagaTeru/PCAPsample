<?php
require_once "db.php";
require_once "functionlib2.php";
?>
<html>
<head>
	<title>Artwork details</title>
</head>
<body>
	<?php include "header.php"; ?>
	<div class="container">
<?php
	$artworkid=$_GET["artworkid"];
	echo '<div class= col-xs-12 id="title">';
	publicPrintTitle($artworkid);
	echo '</div>';
	echo '<div class= "col-xs-6" id="images">';
	publicPrintImages($artworkid);
	echo '</div>';
	echo '<div class= "col-xs-6" id="info">';
	publicPrintFields($artworkid);
	publicPrintMetaFields($artworkid, array("Artwork Repository","Artist Status","Media","Genre","Technique","Attributes/Color","Style"));
	echo '</div>';
	?>
	</div>
</body>