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


			// login logic below
  	   	$stmt = $conn->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=? AND Password =?");
		$stmt->bind_param("ss", $login, $password);
		$stmt->execute();
		$result = $stmt->get_result();
			// login logic above
	//login info
		if ($row = $result->fetch_assoc()  )
		{			//if a user with these credentials exists -> login
			$_SESSION['user'] = $row['ID'];
			returnWithInfo( $row['firstName'], $row['lastName'], $row['ID'], "Logged in!");
		}
	//login info

		else
		{
		//register logib below
			$query = "INSERT INTO `Users` (FirstName,LastName,Login, Password) 
      	   	VALUES ('$firstName','$lastName','$login', '".md5($password)."')";
           	$results = mysqli_query($conn, $query);

			
           	$query = "INSERT INTO `Users` (FirstName,LastName,Login, Password) 
      	   	VALUES ('$firstName','$lastName','$login', '$password')";
      	   	$results = mysqli_query($conn, $query);
      	//register logic above

  	   	// login logic below
      	   	$stmt = $conn->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=? AND Password =?");
			$stmt->bind_param("ss", $login, $password);
			$stmt->execute();
			$result = $stmt->get_result();
		// login logic above

		//login info
			if ($row = $result->fetch_assoc()  )
      	   	{
				$_SESSION['user'] = $row['ID'];
				returnWithInfo( $row['firstName'], $row['lastName'], $row['ID'], "Saved!");
			}
		//login info

			
			

      	   	
			exit();
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
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $firstName, $lastName, $id ,$err)
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
		
	}
	
?>
