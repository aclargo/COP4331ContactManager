const urlBase = 'http://cop4331group11.com/LAMPAPI';
const extension = 'php';

// let userId = ;
let firstName = "";
let lastName = "";

function doLogin()
{
	userId = 0;
	firstName = "";
	lastName = "";

	let login = document.getElementById("loginName").value;
	let password = document.getElementById("loginPassword").value;
    //var hash = md5( password );

	document.getElementById("loginResult").innerHTML = "";

	let tmp = {login:login,password:password};
    //var tmp = {login:login,password:hash};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/login.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				let jsonObject = JSON.parse( xhr.responseText );
				userId = jsonObject.id;

				if( userId < 1 )
				{
					document.getElementById("loginResult").innerHTML = "Username or Password invalid";
					return;
				}

				firstName = jsonObject.firstName;
				lastName = jsonObject.lastName;

				saveCookie();

				window.location.href = "./search.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}
	console.log(userId);

}

function doRegistration()
{
	userId = 0;
	firstName = "";
	lastName = "";

	firstName = document.getElementById("firstName").value;
	lastName = document.getElementById("lastName").value;
	let login = document.getElementById("loginName").value;
	let password = document.getElementById("loginPassword").value;

    //var hash = md5( password );

	if( firstName == "")
	{
		document.getElementById("signupResult").innerHTML = "First Name required";
		return;
	}
	if( lastName == "")
	{
		document.getElementById("signupResult").innerHTML = "Last Name required";
		return;
	}
	if( login == "")
	{
		document.getElementById("signupResult").innerHTML = "Username required";
		return;
	}
	if( password == "")
	{
		document.getElementById("signupResult").innerHTML = "Password required";
		return;
	}
	document.getElementById("signupResult").innerHTML = "";
	if(password.length < 8)
	{
		document.getElementById("passResult").innerHTML = "Password must be 8 letters";
		return;
	}


	let tmp = {FirstName:firstName,LastName:lastName,login:login,
			   password:password};
    //var tmp = {login:login,password:hash};
	let jsonPayload = JSON.stringify(tmp);

	let url = urlBase + '/register.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				let jsonObject = JSON.parse( xhr.responseText );
				
				userId = jsonObject.id;
				firstName = jsonObject.firstName;
				lastName = jsonObject.lastName;
				console.log("UserID " + userId);
				saveCookie();

				window.location.href = "./search.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("signupResult").innerHTML = err.message;
	}

}

function redirectSignUp()
{
	window.location.href = "./signup.html";
}
function redirectLogIn()
{
	window.location.href = "./index.html";
}
function redirectSearch()
{
	window.location.href = "./search.html";
}
function redirectAddContact()
{
	window.location.href = "./addcontact.html";
}
function redirectContacts()
{
	window.location.href = "./contacts.html";
}

function saveCookie()
{
	let minutes = 20;
	let date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	let data = document.cookie;
	let splits = data.split(",");
	for(var i = 0; i < splits.length; i++)
	{
		let thisOne = splits[i].trim();
		let tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
			console.log(userId);
		}
	}

	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
		// document.getElementById("userName").innerHTML = "Logged in as " + firstName + " " + lastName;
	}
}

function doLogout()
{
	userId = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}

function addContact()
{
	readCookie();
	console.log(userId);
	let Name = document.getElementById("fullName").value;
	let Email = document.getElementById("email").value;
	let Phone = document.getElementById("phone").value;
	document.getElementById("contactAddResult").innerHTML = "";


	if( Name == "")
	{
		document.getElementById("contactAddResult").innerHTML = "Name required";
		return;
	}
	if( Email == "")
	{
		document.getElementById("contactAddResult").innerHTML = "Email required";
		return;
	}
	if( Phone == "")
	{
		document.getElementById("contactAddResult").innerHTML = "Phone# required";
		return;
	}


	let tmp = {Name:Name,phone:Phone,email:Email,userId:userId};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/addcontact.' + extension;
	console.log( JSON.stringify(tmp, null, "    ") );

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	console.log("Name: " + Name);
	console.log("Email: " + Email);
	console.log("Phone: " + Phone);
	console.log("UserID " + userId);
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("contactAddResult").innerHTML = "The Contact has been added";
			}
		};
		xhr.send(jsonPayload);
		//window.location.href = "search.html";
	}
	catch(err)
	{
		document.getElementById("contactAddResult").innerHTML = err.message;
	}

}

function searchContact()
{

	readCookie();
	console.log(userId);
	let srch = document.getElementById("searchPhone").value;
	document.getElementById("contactSearchResult").innerHTML = "";
	document.getElementById("mainTab").innerHTML = "";
	document.getElementById("mainTab").innerHTML += '<label id="contactNameResult" style="display: none;"></label>';
	document.getElementById("mainTab").innerHTML += '<label id="contactEmailResult" style="display: none;"></label>';
	document.getElementById("mainTab").innerHTML += '<label id="contactPhoneResult" style="display: none;"></label>';


	//let contactList = "";

	let tmp = {search:srch,userId:userId};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/search.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				//document.getElementById("contactSearchResult").innerHTML = "Contact(s) has been retrieved";
				let jsonObject = JSON.parse( xhr.responseText );
				if(jsonObject.results === undefined){
					document.getElementById("contactSearchResult").innerHTML = "";
					return;
				}
				for( let i=0; i<jsonObject.results.length; i++ )
				{
					//contactList += jsonObject.results[i];
						let name = jsonObject.results[i].Name;
						let phone = jsonObject.results[i].Phone;
						let email = jsonObject.results[i].Email;
						let contactID = jsonObject.results[i].ID;
						console.log(name);
						console.log(phone);
						console.log(email);

							

						document.getElementById("mainTab").innerHTML += `
						<tr>
							<td id=editName> ${document.getElementById("contactNameResult").innerHTML = name}</td>
                        	<td id=editEmail> ${document.getElementById("contactEmailResult").innerHTML = email}</td>
                        	<td id=editPhone> ${document.getElementById("contactPhoneResult").innerHTML = phone}</td>
							<td>
							<img src="css/images/edit.png" style="cursor:pointer;width: 20px;height: 20px;" onClick="editContact('${name}','${email}',${phone},${contactID});" button type="button">
							<img src="css/images/trash_icon1.png" style="cursor:pointer;width: 20px;height: 20px;" onClick="deleteContact(${contactID});" button type="button">
							<div style="width: 0px; height: 19px; margin-top: -25px;"id=saveResult></div>
							</td>
						</tr>`
						// <button onClick="deleteContact(${contactID});">DEL</button>
						// document.getElementById("mainTab").innerHTML += "<tr>"
						// document.getElementById("contactNameResult").innerHTML += name;
						// document.getElementById("contactPhoneResult").innerHTML += phone;
						// document.getElementById("contactEmailResult").innerHTML += email;
						// document.getElementById("mainTab").innerHTML += "</tr>"

				}
				//document.getElementsByTagName("p")[0].innerHTML = contactList;
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}

}

function deleteContact(contactID)
{
	document.getElementById("contactDeleteResult").innerHTML = "";
    if (confirm("Are you sure you want to delete this contact?")) 
	{
        let tmp = {ID: contactID};
        let jsonPayload = JSON.stringify(tmp);
		console.log("Contact ID" + JSON.stringify(tmp, null, "    ") );
       
        let url = urlBase + '/deletecontact.' + extension;

        let xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
        try
        {
            xhr.onreadystatechange = function() 
            {
                if (this.readyState == 4 && this.status == 200) 
                {
                    document.getElementById("contactDeleteResult").innerHTML = "";
                }
            };
            xhr.send(jsonPayload);
        }
        catch(err)
        {
            document.getElementById("contactDeleteResult").innerHTML = err.message;
        }
    }
}

function editContact(oldName,oldEmail,oldPhone,contactID) 
{
	document.getElementById("contactEditResult").innerHTML = "";

	document.getElementById("editName").innerHTML = "<input id='inputName' type='text'>";
	document.getElementById("editEmail").innerHTML = "<input id='inputEmail' type='text'>";
	document.getElementById("editPhone").innerHTML = "<input id='inputPhone'type='text'>";

	document.getElementById("inputName").value = oldName;
	document.getElementById("inputEmail").value = oldEmail;
	document.getElementById("inputPhone").value = oldPhone;

	document.getElementById("saveResult").innerHTML = '<img src="css/images/save.png" style="cursor:pointer;width: 20px;height: 20px; margin-right: -60px;" button id="saveBtn" button type="button">'
	const element = document.getElementById("saveBtn");
	
	element.addEventListener("click",() => {
	let newName = document.getElementById("inputName").value;
    let newEmail = document.getElementById("inputEmail").value;
    let newPhone = document.getElementById("inputPhone").value;

	if(newName == "" )
	{
		return;
	}
	if(newEmail == "")
	{
		return;
	}
	if(newPhone == "")
	{
		return;
	}

	document.getElementById("editName").innerHTML = newName;
	document.getElementById("editEmail").innerHTML = newEmail;
	document.getElementById("editPhone").innerHTML = newPhone;

	let tmp = {Name:newName,phone:newPhone,email:newEmail,ID:contactID};
    let jsonPayload = JSON.stringify(tmp);

    let url = urlBase + '/editcontact.' + extension;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try
    {
        xhr.onreadystatechange = function() 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("contactEditResult").innerHTML = "";
            }
        };
        xhr.send(jsonPayload);
    }
    catch(err)
    {
        document.getElementById("contactEditResult").innerHTML = err.message;
    }
	document.getElementById("saveResult").value = "";
	document.getElementById("saveBtn").value = "";
	});
	
}