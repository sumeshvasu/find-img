<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Insert title here</title>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js"></script>
<link type="text/css" rel="stylesheet" href="http://cdn.datatables.net/1.10.6/css/jquery.dataTables.css" media="all" />
</head>
<body>
<?php
include_once 'Crawler.php';

$crawlerObj = new Crawler();

if( isset( $argv[1] ) ) {
	$pageUrl = $argv[1];	
} else { 
	$pageUrl = 'http://greenpepper.in';
} 

// $pageUrl = 'greenpepper.in';


$bodyTags = $crawlerObj->getbody( $pageUrl );

$anchorTagArray = $crawlerObj->anchorTag( $bodyTags, $pageUrl );

$imgArray = array();

foreach ( $anchorTagArray as $key => $value ){
	$bodyTagsForEachPage = $crawlerObj->getbody( $value );	
	if( isset( $bodyTagsForEachPage ) ) {
		$startTime = microtime(true);
		$imgArray[$value]['processingTime'] = microtime(true) - $startTime;
		$imgArray[$value]['imgTagCount'] = $crawlerObj->imgTagCount( $bodyTagsForEachPage );
	}
}
?>
<table id="example" class="display" cellspacing="0" width="100%">
	<thead>
		<th>URL</th>
		<th>URL PROCESSING TIME</th>
		<th>IMG TAG COUNT</th>
	</thead>
	
<?php 

if( count( $imgArray ) > 0 ){
	foreach ( $imgArray as $key => $value ) {
	?>
	<tr>
		<td><?php echo $key;?></td>
		<td><?php echo $value['processingTime'];?></td>
		<td><?php echo $value['imgTagCount'];?></td>
	</tr>
	<?php 
	}
}
?>
</table>

<script>
$(document).ready(function() {
    $('#example').DataTable();
    $('#example_filter').hide();
} );
</script>
</body>
</html>
