<?php
session_start();
if (!$_SESSION['logged']) {
	header("Location: ../login.php");
	exit;
}

include ('../info.php'); //Database connection

$search3 = $_SESSION['cons3'];
/* vars for export */
// database record to be exported
$db_record = 'document';

// filename for export
$csv_filename = 'db_export_'.$db_record.'_'.date('Y-m-d').'.csv';

// create empty variable to be filled with export data
$csv_export = '';

// query to get data from database
require('search_query.php');
$field3 = mysql_num_fields($run3);

// create line with field names
for($i = 0; $i < $field3; $i++) {
  $csv_export.= mysql_field_name($run3,$i).';';
}
// newline (seems to work both on Linux & Windows servers)
$csv_export.= '
';
// loop through database query and fill export variable
while($row3 = mysql_fetch_array($run3)) {
  // create line with field values
  for($i = 0; $i < $field3; $i++) {
    $csv_export.= '"'.$row3[mysql_field_name($run3,$i)].'";';
  }	
  $csv_export.= '
  ';	
}
// // Convert the data to UTF8, export and prompt a csv file for download
header("Content-Encoding: UTF-8");
header("Content-type: text/x-csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=".$csv_filename."");
echo "\xEF\xBB\xBF"; // UTF-8 with BOM
echo($csv_export);
?>