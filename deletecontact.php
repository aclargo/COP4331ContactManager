
<?php

	$inData = getRequestInfo();
	
	$Id = $inData["ID"];

	$conn = new mysqli("localhost", "Group11", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		
		if(empty($Id))
		{
			returnWithError("Please fill in all fields");
		}
		else
		{
			//code to delete contact
			$sql = "DELETE FROM Contacts WHERE id='$Id'";
			if(mysqli_query($conn, $sql)){
			    sendResultInfoAsJson("Contact was deleted successfully.");
			} else {
			    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
			}
		}

		
	}
	
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $firstName, $lastName, $id )
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>
