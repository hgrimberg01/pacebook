<?php
class User_model extends CI_Model {
	function __construct() {
		// Call the Model constructor
		parent::__construct ();
	}
	function getUser($userID) {
		$sql = "SELECT firstName, lastName, username FROM Users WHERE userId = ?;";
		
		$qry = $this->db->query ( $sql, array (
				$userID 
		) );
		
		return $qry->result ()[0];
	}
	function getAllUsers() {
		$qry = "SELECT userID as uid, userName as uName, firstName as fName, lastName as lName FROM Users;";
		
		$qrye = $this->db->query ( $qry );
		
		return $qrye->result ();
	}
	function getUserGlobalPermission($userId) {
		// Returns an array of permissionIDs a user has, resolving nested permissions to the maximum level.
		$sql = "SELECT MAX(userAccessLevel) as level
			FROM `globalAccessGrants` WHERE `userId` = ?";
		$param = array (
				$userId 
		);
		
		$res = $this->db->query ( $sql, $param );
		
		return $res->result ()[0]->level;
	}
	function getUserNetworkPermissions($userID) {
	}
	function putUser($uName, $firstName, $lastName, $phone, $address, $email, $password) {
		$cleaned_uName = $this->db->escape ( $uName );
		$cleaned_email = $this->db->escape ( $email );
		$check_username_email_qry = 'SELECT userName , userEmail FROM Users WHERE userName =' . $cleaned_uName . ' OR userEmail =  ' . $cleaned_email . ';';
		
		$query = $this->db->query ( $check_username_email_qry );
		if ($query->num_rows () > 0) {
			throw new Exception ( 'USER_EXISTS' );
		} else {
			
			$cleaned_phone = $this->db->escape ( $phone );
			$cleaned_address = $this->db->escape ( $address );
			$cleaned_password = $this->db->escape ( $password );
			$cleaned_firstname = $this->db->escape ( $firstName );
			$cleaned_lastname = $this->db->escape ( $lastName );
			
			$encrypt_password = $this->db->escape ( password_hash ( $cleaned_password, PASSWORD_BCRYPT ) );
			
			$dateTime = $this->db->escape ( date ( 'Y-m-d H:i:s' ) );
			
			$new_user_qry = "INSERT INTO `qchen`.`Users` (`userID`, `userName`, 
					`userAddress`, `userPhone`, `userPassword`, `userCreationDate`,
					 `userActive`, `userImageID`, `userEmail`, `firstName`, `lastName`)
					 VALUES (NULL, " . $cleaned_uName . ", " . $cleaned_address . ", " . $cleaned_phone . ", " . $encrypt_password . ", 
					" . $dateTime . ", 1, NULL, " . $cleaned_email . ", " . $cleaned_firstname . ", " . $cleaned_lastname . ");";
			
			$retn = $this->db->query ( $new_user_qry );
			
			return $this->db->insert_id ();
		}
	}
	function checkPassword($username_email, $password) {
		$cleaned_password = $this->db->escape ( $password );
		
		$qry = "SELECT userID,userPassword  FROM `Users` WHERE (userName = ? OR userEmail = ?);";
		
		$param = array (
				$username_email,
				$username_email 
		);
		
		$res = $this->db->query ( $qry, $param );
		$nrows = $res->num_rows ();
		
		$user_id = - 3;
		$get_pass = '';
		
		if ($nrows == 0 || $nrows > 1) {
			return - 1;
		} else {
			
			foreach ( $res->result () as $row ) {
				$user_id = $row->userID;
				$get_pass = $row->userPassword;
			}
			
			if (password_verify ( $cleaned_password, $get_pass )) {
				return $user_id;
			} else {
				return - 2;
			}
		}
	}
	function checkFriendShip($userOne, $userTwo) {
		$qry = "SELECT firstUserId,secondUserId FROM `friendship` WHERE firstUserId = ? AND secondUserId = ? AND isApproved = 1";
		$param = array (
				$userOne,
				$userTwo 
		);
		
		$res = $this->db->query ( $qry, $param );
		if ($res->num_rows () == 0) {
			return true;
		} else {
			return false;
		}
	}
	function addFriendShip($userOne, $userTwo) {
	}
	function confirmFriendShip($userOne, $userTwo) {
		$qry = "UPDATE `friendship` SET `isApproved` = 1, approvalDate = ? WHERE firstUserId = ? AND secondUserId = ? ;";
		$param = array (
				$this->db->escape ( date ( 'Y-m-d H:i:s' ) ),
				$userOne,
				$userTwo 
		);
		
		$res = $this->db->query ( $qry, $param );
	}
	function getNetworks($user) {
		$qry = "SELECT networks.networkName as name FROM networkMembership NATURAL JOIN networks WHERE userID = ?";
		
		$param = array (
				$user 
		);
		
		$res = $this->db->query ( $qry, $param );
		
		return $res->result ();
	}
	function setNetworks($userId, $networkIds) {
		$sql = "INSERT INTO `networkMembership` (`networkID`, `userID`, `accessLevel`, `requestDate`, `approvalDate`, `approvedByUserID`) VALUES (?,?,?,?,?,?);";
		$date = $this->db->escape ( date ( 'Y-m-d H:i:s' ) );
		foreach ( $networkIds as $nid ) {
			$params = array (
					$nid,
					$userId,
					4,
					$date,
					$date,
					5 
			);
			$this->db->query ( $sql, $params );
		}
		return;
	}
	function getFriends($user) {
		$qry = "(SELECT friendship.secondUserID as friendID ,
				 Users.username, Users.firstName as fName, Users.lastName as lName FROM 
				`friendship`,Users WHERE  friendship.firstUserId = ? AND
				 Users.userId =friendship.secondUserId)
				UNION
				(SELECT friendship.firstUserID as friendID,
				 Users.username, Users.firstName as fName, Users.lastName as lName
				 FROM `friendship`,Users WHERE 
				 friendship.secondUserId = ? AND Users.userId =friendship.firstUserId)";
		
		$param = array (
				
				$user,
				$user 
		);
		
		$res = $this->db->query ( $qry, $param );
		
		return $res->result ();
	}
	function checkAdminRights($userId, $groupID) {
	}
	function grantGlobalPerms($userId, $permLevel) {
		$sql = "INSERT INTO `globalAccessGrants` (`userId` ,`userAccessLevel` ,`grantDateTime`)
			VALUES (?, ?, ?);";
		
		$param = array (
				$userId,
				$permLevel,
				$this->db->escape ( date ( 'Y-m-d H:i:s' ) ) 
		);
		
		$this->db->query ( $sql, $param );
		return;
	}
	function searchUsers($qry) {
		$terms_space = explode ( " ", $qry );
		
		// Will return a list of UID in order of relevance
	}
}
?>