<?php
	$inData = getRequestInfo();
	
	// $id = 0;
	$firstName = trim($inData["FirstName"]);
	$lastName = trim($inData["LastName"]);
	//$email = trim($inData["email"]);
	$login = trim($inData["login"]);
	$password = trim($inData["password"]);
// 	$confirmpassword = trim($inData["confirmpassword"]);


	$conn = new mysqli("localhost", "Group11", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	//if it connects to the server we can start filling out the text fields
	else
	{
		//need to add an elseif to check if the username is already taken.
		$sql_u = "SELECT * FROM Users WHERE Login='$login'";
		$res_u = mysqli_query($conn, $sql_u);
		
		// need to add somewhere in here code to add user to the database.

		//check if all fields are full
		if(
			empty($firstName)
			||empty($lastName)
			||empty($login)
			||empty($password)
// 			||empty($confirmpassword)
// 			||empty($email)
			)
		{
			returnWithError("Please fill in all fields");
		}
		elseif (mysqli_num_rows($res_u) > 0) {
		    // username already exists
		    returnWithError("Username is already taken");
		}
		//validate email
// 		elseif(!filter_var($email,FILTER_VALIDATE_EMAIL))
// 		{
// 			returnWithError("Not a valid email");
// 		}
		//check pass length
		elseif(strlen($password) < 8 )
		{
			returnWithError("Password needs 8 characters minimum");
		}
		//confirm pass
// 		elseif ($confirmpassword != $password) 
// 		{
// 			returnWithError("Passwords do not match");
// 		}
		else 
		{
			//add the user to the users table here
			$query = "INSERT INTO Users (FirstName,LastName,Login, password) 
      	   	VALUES ('$firstName','$lastName','$login', '".md5($password)."')";

           	$results = mysqli_query($conn, $query);
           	$query = "INSERT INTO Users (FirstName,LastName,Login, password) 
      	   	VALUES ('$firstName','$lastName','$login', '$password')";
      	   	$results = mysqli_query($conn, $query);
           	echo 'Saved!';
//            	returnWithError("Something isnt wrong");
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

	function returnWithInfo( $firstName, $lastName, $id )
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
		
	}
	
?>
