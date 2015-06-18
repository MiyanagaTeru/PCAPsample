<?php
require_once "db.php";
function publicPrintTitle($artworkid){
	global $db, $con; //prepare to connect to db
	//get artwork's title
	$sql = "SELECT `Title` FROM `artwork` WHERE `id`='$artworkid'";
	if ($result=mysqli_query($con, $sql))//execute the statement 
		while ($row =mysqli_fetch_row($result))
		{
			$title=$row[0];
		}
	echo $title;	
}
function publicPrintImages($artworkid){

	global $db, $con; //prepare to connect to db
	//check if this artwork is public
	//get artist id and guid
	$sql = "SELECT `artist id` FROM `artwork` WHERE `id`='$artworkid'";
	if ($result=mysqli_query($con, $sql))//execute the statement 
		while ($row =mysqli_fetch_row($result))
		{
			$artistid=$row[0];
		}
	//check artist's permission	
	$sql = "SELECT Permission FROM `artist` WHERE `id`='$artistid'";
	if ($result=mysqli_query($con, $sql)){//execute the statement 
		while ($row =mysqli_fetch_row($result))
		{
			$permission=$row[0];
		}
	}
	//if permission is true
	if ($permission === "true") {
		//count how many images are in the folder in content
		$sql = "SELECT `id` FROM `image_file` WHERE `artwork id`='$artworkid'";
		if ($result=mysqli_query($con, $sql))//execute the statement 
			while ($row =mysqli_fetch_row($result))
			{
				$imageids[]=$row[0];
			}
		//start to print out images
		if (count($imageids) > 0){
			foreach ($imageids as $imageid) {
				echo '<img class="img-responsive" src="getimage.php?imageid='.$imageid.'">';
			}
		}
	}
	else {
		
		if (strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME'].'/admin/')>-1 ){//seems only admin is protected??
			//count how many images are in the folder in content
			//echo "we are here";
			$sql = "SELECT `id` FROM `image_file` WHERE `artwork id`='$artworkid'";
			if ($result=mysqli_query($con, $sql))//execute the statement 
				while ($row =mysqli_fetch_row($result))
				{
					$imageids[]=$row[0];
				}
			//start to print out images
			if (count($imageids) > 0){
				foreach ($imageids as $imageid) {
					echo "This is a private image. It will be hidden in the public interface.";
					echo '<img class="img-responsive" src="getimage.php?imageid='.$imageid.'">';
				}
			}
		}
		else {
			echo '<img class="img-responsive" src="blank.png">';
		}
	}
}
function publicPrintFields($artworkid)
{
	global $db, $con;
	//get guid, artist id and show id	
	$sql = "SELECT guid, `artist id`, `show id` FROM `artwork` WHERE `id`='$artworkid'";
		if ($result=mysqli_query($con, $sql))//execute the statement and save result to $result
		{
			while ($row =mysqli_fetch_row($result))
			{
				$guid = $row[0];
				$artistid = $row[1];
				$showid = $row[2];
			}
		}
	//print artist's name
	echo '<h5>Artist:</h5>';			
 	//check artist's permission for name
	$sql = "SELECT Permission FROM `artist` WHERE `id`='$artistid'";
	if ($result=mysqli_query($con, $sql)){//execute the statement 
		while ($row =mysqli_fetch_row($result))
		{
			$permission=$row[0];
		}
	} 	
	$sql = "SELECT private FROM `artist_metadata` WHERE `artist id`='$artistid' AND attributename='First Name'";
	if ($result=mysqli_query($con, $sql)){//execute the statement 
		while ($row =mysqli_fetch_row($result))
		{
			$firstnameP=$row[0];
		}
	}
	$sql = "SELECT private FROM `artist_metadata` WHERE `artist id`='$artistid' AND attributename='Last Name'";
	if ($result=mysqli_query($con, $sql)){//execute the statement 
		while ($row =mysqli_fetch_row($result))
		{
			$lastnameP=$row[0];
		}
	}	
	echo '<p>Painter: ';
	//if has permission and name is not private
	if ($permission == "true" && $firstnameP == "false" || $lastnameP =="false" )	{
		//get artist data
		if ($firstnameP == "false"){
			$sql = "SELECT `First Name` FROM `artist` WHERE `id`='$artistid'";
			if ($result=mysqli_query($con, $sql)){//execute the statement 
				while ($row =mysqli_fetch_row($result))
				{
					$firstname=$row[0];
				}
			}
			echo $firstname.' ';
		}
		if ($lastnameP == "false"){
			$sql = "SELECT `Last Name` FROM `artist` WHERE `id`='$artistid'";
			if ($result=mysqli_query($con, $sql)){//execute the statement 
				while ($row =mysqli_fetch_row($result))
				{
					$lastname=$row[0];
				}
			}
			echo $lastname;
		}		
	}
	else {
		if (strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME'].'/admin/')>-1 ){//seems only admin is protected??
			//get artist data
			$sql = "SELECT `First Name`,`Last Name` FROM `artist` WHERE `id`='$artistid'";
			if ($result=mysqli_query($con, $sql)){//execute the statement 
				while ($row =mysqli_fetch_row($result))
				{
					$firstname=$row[0];
					$lastname=$row[1];
				}
			}
			echo $firstname.' '.$lastname.'(private)';
		}
		else {
			echo 'Anonymous';				
		}
	}
	echo ('</p>');			
	//get artist's name
	$sql = "SELECT private FROM `artist_metadata` WHERE `artist id`='$artistid' AND attributename='Artist Name'";

	if ($result=mysqli_query($con, $sql)){//execute the statement 
		while ($row =mysqli_fetch_row($result))
		{
			$artistnameP=$row[0];
		}
	}		
	if ($permission == "true" && $artistnameP == "false" ){	
		//get artist metadata
		$sql = "SELECT attributevalue FROM `artist_metadata` WHERE `artist id`='$artistid' AND attributename='Artist Name'";
			//echo $sql;
		if ($result=mysqli_query($con, $sql)){//execute the statement 
			while ($row =mysqli_fetch_row($result))
			{
				$artistname=$row[0];
			}
		}
		if ($artistname!=''){
			echo '<h5>Artist Name:</h5>';		
			echo '<p>'.$artistname.'</p>';
		}
	}
	else {
		if (strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME'].'/admin/')>-1 ){//seems only admin is protected??
			//get artist data
			$sql = "SELECT attributevalue FROM `artist_metadata` WHERE `artist id`='$artistid' AND attributename='Artist Name'";
			if ($result=mysqli_query($con, $sql)){//execute the statement 
				while ($row =mysqli_fetch_row($result))
				{
					$artistname=$row[0];
				}
			}
			if ($artistname!=''){
				echo '<h5>Artist Name:</h5>';							
				echo '<p>'.$artistname.'(private)</p>';
			}
	}


	}	
	//get show data
	$sql = "SELECT Type, Year, Location FROM `show` WHERE `id`='$showid'";
	if ($result=mysqli_query($con, $sql)){//execute the statement 
		while ($row =mysqli_fetch_row($result))
		{
			$showtype=$row[0];
			$showyear=$row[1];			
			$showloc=$row[2];
		}
	}
	echo '<h5>Show:</h5>';
	echo '<p>'.$showtype.', '.$showyear.'</p>';	
	echo '<p>'.$showloc.'</p>';	
	
}
function publicPrintMetaFields($artworkid, array $fields){
	global $db, $con;
	$altfields = array();//define an empty array. 
	$sql = "SELECT attribute FROM attributes WHERE metadatatype = 'artwork' AND `default` LIKE '0' ORDER BY sortorder ASC"; //prepare statement to get target info. put ``around default to distinguish it from system reserved variable default. sorted by sortorder to modify its presenting orders on web page.
	if ($result=mysqli_query($con, $sql))//execute the statement and save result to $result
	{
		while ($row =mysqli_fetch_row($result))//loop through each row of the result
		{
			{
				$altfields[] = $row[0]; //$row is an array like[attribute, default values, help, sortorder,validation]. $info is an multi-dimension array like [[attibutes, default values, help],[attribute, default values, help, sortorder],...[attribute, default values, help, sortorder]]
			}
		}
	}
	$fields=array_unique(array_merge($fields,$altfields));
		//get artwork metadata
	foreach ($fields as $field) {
		$value="";
		$sql = "SELECT attributevalue FROM `artwork_metadata` WHERE `artwork id`='$artworkid' AND attributename='$field'";
		if ($result=mysqli_query($con, $sql)){//execute the statement 
			while ($row =mysqli_fetch_row($result))
			{
				$value=$row[0];
			}
		}
		$value = str_replace(",,", ", ", $value);
		if ($value){
			echo '<h5>'.$field.':</h5>';
			echo '<p>'.$value.'</p>';
		}
		
	}
}
?>