<?php
//Class Autoloader
spl_autoload_register(function ($className) {

    $className = strtolower($className);
    $path = "admin/{$className}.php";

    if (file_exists($path)) {

        require_once($path);

    } else {

        die("The file {$className}.php could not be found.");

    }
});

    



function deleteUser($username)
{
		$SQLservername = "localhost";
		$SQLusername = "root";
		$SQLpassword = "password";
		$SQLdbname = "cube";

		// Create connection
		$conn = new mysqli($SQLservername, $SQLusername, $SQLpassword, $SQLdbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "DELETE FROM members WHERE username='bob'";
		$result = $conn->query($sql);
		$tblData = '';
		
		echo 'alert("message successfully sent")';
		
		getUsers();
		
		//$tblData = '';
		
		return $username;
		
  
	
}


function getUsers()
{
	try {
		
		$SQLservername = "localhost";
		$SQLusername = "root";
		$SQLpassword = "password";
		$SQLdbname = "cube";
		
		// Create connection
		$conn = new mysqli($SQLservername, $SQLusername, $SQLpassword, $SQLdbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "SELECT username, email, verified, admin, mod_timestamp FROM members order by admin desc, username";
		$result = $conn->query($sql);
		$tblData = '';
		while($row = $result->fetch_assoc()) {
			
	
			if ($row["admin"] == "1"){
				if ($row["verified"] == "1"){
					$tblData .= "<tr align='middle'><td>" . $row["username"]. "</td><td>" . $row["email"]. "</td><td><i class='fa fa-check'></i></td><td><i class='fa fa-shield'></i></td><td>". $row["mod_timestamp"]. "</td>";
				}
				else{
					$tblData .= "<tr align='middle'><td>" . $row["username"]. "</td><td>" . $row["email"]. "</td><td><i class='fa fa-times'></i></td><td><i class='fa fa-shield'></i></td><td>". $row["mod_timestamp"]. "</td>";

				}
			}
			else{
				if ($row["verified"] == "1"){
					$tblData .= "<tr align='middle'><td>" . $row["username"]. "</td><td>" . $row["email"]. "</td><td><i class='fa fa-check'></i></td><td></td><td>" . $row["mod_timestamp"]. "</td>". $row["mod_timestamp"]. "</td>";
				}
				else{
					$tblData .= "<tr align='middle'><td>" . $row["username"]. "</td><td>" . $row["email"]. "</td><td><i class='fa fa-times'></i></td><td></td><td>" . $row["mod_timestamp"]. "</td><td>". $row["mod_timestamp"]. "</td>";

				}
			}
    }
		return $tblData;
		
	} catch (PDOException $e) {

        $err = "Error: " . $e->getMessage();

    }
	//Determines returned value ('true' or error code)
    $resp = ($err == '') ? 'true' : $err;

    return $resp;
	
}

function checkAttempts($username)
{

    try {

        $db = new DbConn;
        $conf = new GlobalConf;
        $tbl_attempts = $db->tbl_attempts;
        $ip_address = $conf->ip_address;
        $err = '';

        $sql = "SELECT Attempts as attempts, lastlogin FROM ".$tbl_attempts." WHERE IP = :ip and Username = :username";

        $stmt = $db->conn->prepare($sql);
        $stmt->bindParam(':ip', $ip_address);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;

        $oldTime = strtotime($result['lastlogin']);
        $newTime = strtotime($datetimeNow);
        $timeDiff = $newTime - $oldTime;

    } catch (PDOException $e) {

        $err = "Error: " . $e->getMessage();

    }

    //Determines returned value ('true' or error code)
    $resp = ($err == '') ? 'true' : $err;

    return $resp;

};

function mySqlErrors($response)
{
    //Returns custom error messages instead of MySQL errors
    switch (substr($response, 0, 22)) {

        case 'Error: SQLSTATE[23000]':
            echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Username or email already exists</div>";
            break;

        default:
            echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>An error occurred... try again</div>";

    }
};


