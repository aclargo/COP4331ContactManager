
<?php

	$inData = getRequestInfo();
	
	$userId = $inData["userId"];
	$Name = trim($inData["Name"]);
	$email = trim($inData["email"]);
	$phone = trim($inData["phone"]);
	// $lastName = trim($inData["LastName"]);
	// $confirmpassword = trim($inData["confirmpassword"]);
	// $login = trim($inData["login"]);

	$conn = new mysqli("localhost", "Group11", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		if(
			empty($Name)
			||empty($phone)
			||empty($email)
			)
		{
			returnWithError("Please fill in all fields");
		}
		elseif(!filter_var($email,FILTER_VALIDATE_EMAIL))
		{
			returnWithError("Not a valid email");
		}
		//should we add an elseif to make sure the number is 10 digits
		//ivans vote is no -->(i.e. 911)
		//we should add code to make sure there are no letters tho
		else
		{
			//check name
			//check email
			//check number		
			$stmt = $conn->prepare("select * from Contacts where (Name like ? or Phone like ? or Email like ?) and UserID=?");
			$stmt->bind_param("ssss", $Name, $phone, $email, $userId);
			$stmt->execute();
			
			$result = $stmt->get_result();
			
			$row = $result->fetch_assoc();
			
			if( $row > 0 )
			{
				//could add functionality to show this contact
				returnWithError("Contact already exists");
			}
			else
			{

				$stmt = $conn->prepare("INSERT into Contacts (UserId,Name,Phone,Email) VALUES(?,?,?,?)");
				$stmt->bind_param("ssss", $userId, $Name, $phone, $email);
				$stmt->execute();
				$stmt->close();
				returnWithInfo("Contact added");
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
		$retValue = '{"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $Results )
	{
		$retValue = '{"results":[' . $Results . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>
