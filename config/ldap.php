<?php
$ldap_conn = ldap_connect("ldap.forumsys.com", 389);
$errors = array();

class LDAP{
	private $ldap_host = "ldap.forumsys.com"; //Provide your own LDAP server here
	private $ldap_user = "cn=read-only-admin,dc=example,dc=com"; //LDAP access for binding
	private $ldap_port = 389;
	private $ldap_password = "password"; //password: $ldap_user
	private $dn = "dc=example,dc=com"; //you can modify this and create your own filter
	private $conn;

	function __construct($db){
		$this->conn = $db;
	}

	function LDAPconnect(){
		global $ldap_conn;
		ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
		putenv ('LDAPTLS_REQCERT = never');
		if($ldap_conn){
			ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set protocol version 3');
			ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0) or die('Unable to set ldap referrals');
			// echo "LDAP connection successful! <br>"; 

			$bind = ldap_bind($ldap_conn, $this->ldap_user, $this->ldap_password);

			if($bind){
				$_SESSION['ldapBind'] = true;
				// echo "Bind Sucscessful";

			}else{
				$_SESSION['ldapBind'] = false;
				// echo "LDAP BINDING ERROR: ".ldap_error($ldap_conn)."<br>";
			}

		}else{
			echo "Can not establish LDAP connection";
		}
	}

	function authenticate($adUser){
		global $ldap_conn;

		$query = ldap_search($ldap_conn, $this->dn, "(uid=*$adUser*)");
		$data = ldap_get_entries($ldap_conn, $query);
		$count = ldap_count_entries($ldap_conn, $query);

		if($data['count'] > 0){

			$samaccount = $data[0]['uid'][0];
			$user = $data;

			$sql = "SELECT * FROM [user] WHERE samaccount=?";
			var_dump($sql);
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $samaccount);
			$stmt->execute();

			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if($row == true){

				$_SESSION['user'] = $row; 

				$_SESSION['user'] = $data;
				$_SESSION['access'] = $row['accessId'];

				if($_SESSION['access'] == 2){
					$_SESSION['message'] = "Hello ". $_SESSION['user'][0]['cn'][0];
					header('location: ./itadmin/index.php');
				}else if($_SESSION['access'] == 1){
					$_SESSION['message'] = "Hello ". $_SESSION['user'][0]['cn'][0];
					header('location: ./csadmin/index.php');
				}else if($_SESSION['access'] == 3 || $_SESSION['access'] == 4){
					$_SESSION['message'] = "Hello ". $_SESSION['user'][0]['cn'][0];
					header('location: ./wh/index.php');
				}else{
					$_SESSION['message'] = "Hello ". $_SESSION['user'][0]['cn'][0];
					header('location: ./part-replacement/index.php');
				}
			}else{
				$_SESSION['user'] = $data;
				$_SESSION['access'] = 5;
				$_SESSION['message'] = "Hello ". $_SESSION['user'][0]['givenname'][0];
				header('location: ./part-replacement/index.php');
			}
			$delimeters = array('=',',');
			$managercn = $data[0]['manager'][0];
			for($i=0;$i<count($delimeters);$i++){
				$manager = explode($delimeters[$i],$managercn);
			}

			echo $manager[0];
			// echo $managercn;
			$search = ldap_search($ldap_conn, $this->dn, "$manager[0]");
			$x = ldap_get_entries($ldap_conn, $search);

			echo $x[0]['displayname'][0];
			return $data;
			
			
			ldap_unbind($ldap_conn);
		}else{
			ldap_unbind($ldap_conn);
			$code = base64_encode("404");
			$desc = base64_encode("Not Found");
			$message = base64_encode("We cannot find your credentials within our system");
			header("location:./error?code=$code&desc=$desc&message=$message");
		}
	}

	function searchUser($name){
		global $ldap_conn;

		$query = ldap_search($ldap_conn, $this->dn, "(uid=*$name*)");
		$data = ldap_get_entries($ldap_conn, $query);
		$count = ldap_count_entries($ldap_conn, $query);
		if($data){
			
			return $data;
			ldap_unbind($ldap_conn);

		}else{
			echo ldap_error($ldap_conn);
			ldap_unbind($ldap_conn);
		}
	}

	
}

?>