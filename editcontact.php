
<?php

	$inData = getRequestInfo();
	
	$ID = $inData["ID"];
	$Name = trim($inData["Name"]);
	$email = trim($inData["email"]);
	$phone = trim($inData["phone"]);

	$conn = new mysqli("localhost", "Group11", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{	
		if(!filter_var( !is_null($email) && $email,FILTER_VALIDATE_EMAIL))
		{

			$sql = "UPDATE Contacts SET Email='$email' WHERE ID='$ID'";
			if(mysqli_query($conn, $sql)){
			    echo "Email was updated successfully.";
			} else {
			    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
			}
			
		}
		
		if(!is_null($phone))
		{
			$sql = "UPDATE Contacts SET Phone='$phone' WHERE ID='$ID'";
			if(mysqli_query($conn, $sql)){
			    echo "Phone was updated successfully.";
			} else {
			    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
			}
		}
		
		if(!is_null($Name))
		{
			$sql = "UPDATE Contacts SET Name='$Name' WHERE Id='$ID'";
			if(mysqli_query($conn, $sql)){
			    echo "Name was updated successfully.";
			} else {
			    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
			}
		}
		
		if(is_null($Name) && is_null($phone) && is_null($email))
		{
			echo "Somthings wrong";
			sendResultInfoAsJson("Nothing updated");
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
