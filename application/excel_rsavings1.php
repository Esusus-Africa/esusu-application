<?php
include "../config/session.php"; 
$dfrom = $_GET['dfrom'];
$dto = $_GET['dto'];
$status = $_GET['status'];
$acctno = $_GET['acctno'];
$id = $_GET['id'];
$SQL = "SELECT * FROM all_savingssub_transaction WHERE date_time BETWEEN '$dfrom' AND '$dto' AND status = '$status' AND acn = '$acctno' OR date_time BETWEEN '$dfrom' AND '$dto' AND status = '$status' AND merchant_id = '$id' ORDER BY id";
$header = '';
$result ='';
$exportData = mysqli_query ($link,$SQL ) or die ("Sql error : " . mysqli_error( ) );
 
$fields = mysqli_num_fields ( $exportData );
 
for ($i = 0; $i < $fields; $i++)
{
   //$header .= mysqli_field_name($exportData,$i) . "\t";
    //echo implode("\t", array_keys($record)) . "\n";
}
 
while( $row = mysqli_fetch_row( $exportData ) )
{
    $line = '';
    foreach( $row as $value )
    {                                            
        if ( ( !isset( $value ) ) || ( $value == "" ) )
        {
            $value = "\t";
        }
        else
        {
            $value = str_replace( '"' , '""' , $value );
            $value = '"' . $value . '"' . "\t";
        }
        $line .= $value;
    }
    $result .= trim( $line ) . "\n";
}
$result = str_replace( "\r" , "" , $result );
 
if ( $result == "" )
{
    $result = "\nNo Record(s) Found!\n";                        
}
 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=export.xls");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$result";
 
?>