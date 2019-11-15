<?php
	include ( '../../PdfToText.phpclass' );
	include (realpath(__DIR__ . '/../../../db.php'));
	error_reporting(E_ALL);

	$pdf 	=  new PdfToText ( );
	$pdf -> Load ( 'nye_opfattelser_af_teknologi.pdf');
	//echo $pdf -> Text ; 		// or you could also write : echo ( string ) $pdf ;

	// Cleaning out certain words and phrases
	$str = str_replace(
		array("Nanna Ellegaard","Årskortnr.: 19983557", "Årskortnr. 19983557", "årskortnr. 19983557", "årskortnr. 19993311", "ae"),
    array("", "", "", "", "", "æ"),
    $pdf -> Text
	);

	echo preg_replace( "/\r|\n/", "", $str);


	// $content = mysqli_real_escape_string($conn, $str);
	// $id = mysqli_real_escape_string($conn, intval($_REQUEST["id"]));
	// $sql = "UPDATE " . $DBtable . " SET content = '$content' WHERE id = '$id'";
	// $rs=$conn->query($sql);
	//
	// if ($rs === TRUE) {
	// 	echo "Record updated successfully";
	// } else {
	// 		echo "Error updating record: " . $conn->error;
	// }
	//
	// $conn->close();
	?>
