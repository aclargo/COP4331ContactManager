<?php
	$inData = getRequestInfo();
	
	// $id = 0;
	$firstName = trim($inData["FirstName"]);
	$lastName = trim($inData["LastName"]);
	$login = trim($inData["login"]);
	$password = trim($inData["password"]);


	$conn = new mysqli("localhost", "Group11", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	//if it connects to the server we can start filling out the text fields
	else
	{
		// need to add somewhere in here code to add user to the database.

		//check if all fields are full
		// if(
		// 	empty($firstName)
		// 	||empty($lastName)
		// 	||empty($login)
		// 	||empty($password)
		// 	)
		// {
		// 	returnWithError("Please fill in all fields");
		// }
		
		// else 
		// {
			//add the user to the users table here

			
			$query = "INSERT INTO `Users` (FirstName,LastName,Login, Password) 
      	   	VALUES ('$firstName','$lastName','$login', '".md5($password)."')";
           	$results = mysqli_query($conn, $query);

			
           	$query = "INSERT INTO `Users` (FirstName,LastName,Login, Password) 
      	   	VALUES ('$firstName','$lastName','$login', '$password')";
      	   	$results = mysqli_query($conn, $query);


      	   	$stmt = $conn->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=? AND Password =?");
			$stmt->bind_param("ss", $login, $password);
			$stmt->execute();
			$result = $stmt->get_result();

      	   	if ($row = $result->fetch_assoc()  )
      	   	{
				$_SESSION['user'] = $row['ID'];
				returnWithInfo( $row['firstName'], $row['lastName'], $row['ID'], "Saved!");
			}


           	// echo '"Saved!"';
           	// returnWithError("Saved!");
			exit();
		// }

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
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $firstName, $lastName, $id ,$err)
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
		
	}
	
?>
