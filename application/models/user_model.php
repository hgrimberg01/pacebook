<?php
class User_model extends CI_Model {
	function __construct() {
		// Call the Model constructor
		parent::__construct ();
	}
	function getUser($userID) {
		$sql = "SELECT firstName, lastName, username from users WHERE userId = ?;";
		
		$qry = $this->db->query ( $sql, array (
				$userID 
		) );
		
		return $qry->result ()[0];
	}
	function getAllusers() {
		$qry = "SELECT userID as uid, userName as uName, firstName as fName, lastName as lName from users;";
		
		$qrye = $this->db->query ( $qry );
		
		return $qrye->result ();
	}
	function getUserGlobalPermission($userId) {
		// Returns an array of permissionIDs a user has, resolving nested permissions to the maximum level.
		$sql = "SELECT MAX(userAccessLevel) as level
			FROM `globalaccessgrants` WHERE `userId` = ?";
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
		$check_username_email_qry = 'SELECT userName , userEmail from users WHERE userName =' . $cleaned_uName . ' OR userEmail =  ' . $cleaned_email . ';';
		
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
			
			$new_user_qry = "INSERT INTO `users` (`userID`, `userName`, 
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
		
		$qry = "SELECT userID,userPassword  FROM `users` WHERE (userName = ? OR userEmail = ?);";
		
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
		$qry = "INSERT INTO `friendship` ( `firstUserId` ,`secondUserId` ,`isApproved` ,`approvalDate` ,`creationDate`) VALUES (?,?,?,?,?); ";
		$param = array (
				$userOne,
				$userTwo,
				'0',
				'NULL',
				date ( 'Y-m-d H:i:s' ) 
		);
		$res = $this->db->query ( $qry, $param );
		
		return;
	}
	function confirmFriendShip($userOne, $userTwo) {
		$qry = "UPDATE `friendship` SET `isApproved` = 1, approvalDate = ? WHERE `firstUserId` = ? AND `secondUserId` = ? ;";
		$param = array (
				date ( 'Y-m-d H:i:s' ),
				$userOne,
				$userTwo 
		);
		
		$res = $this->db->query ( $qry, $param );
		
		return;
	}
	function denyFriendShip($userOne, $userTwo) {
		$qry = "DELETE FROM `friendship`  WHERE `firstUserId` = ? AND `secondUserId` = ? OR `firstUserId` = ? AND `secondUserId` = ?  ;";
		$param = array (
				
				$userOne,
				$userTwo,$userTwo,$userOne
		);
		
		$res = $this->db->query ( $qry, $param );
		
		return;
	}
	function getNetworks($user) {
		$qry = "SELECT networks.networkName as name FROM networkmembership NATURAL JOIN networks WHERE userID = ?";
		
		$param = array (
				$user 
		);
		
		$res = $this->db->query ( $qry, $param );
		
		return $res->result ();
	}
	function getNetworks_IDs($userID) {
		$qry = "SELECT networkID as ID FROM networkmembership WHERE userID = ? AND isApproved=1 ;";
		
		$param = array (
				$userID
		);
		
		$res = $this->db->query ( $qry, $param );
		
		return $res->result ();
	}
	function getPendingNetworkJoins($userID) {
		$qry = "SELECT networkID as ID FROM networkmembership WHERE userID = ? AND isApproved=0 ;";
		
		$param = array (
				$userID
		);
		
		$res = $this->db->query ( $qry, $param );
		
		return $res->result ();
	}
	function getAllNetworkIDs($userID) {
		$qry = "SELECT networkID as ID FROM networkmembership WHERE userID = ? ;";
		
		$param = array (
				$userID
		);
		
		$res = $this->db->query ( $qry, $param );
		
		return $res->result ();
	}
	function putNetworks($userId, $networkIds) {
		$sql = "INSERT INTO `networkmembership` (`networkID`, `userID`, `accessLevel`, `requestDate`, `approvalDate`, `approvedByUserID`, `isApproved`) VALUES (?,?,?,?,?,?,?);";
		$date = $this->db->escape ( date ( 'Y-m-d H:i:s' ) );
		foreach ( $networkIds as $nid ) {
			$params = array (
					$nid,
					$userId,
					4,
					$date,
					$date,
					5,
					1
			);
			$this->db->query ( $sql, $params );
		}
		return;
	}
	function joinNetwork($userID, $networkID) {
		$sql = "INSERT INTO `networkmembership` (`networkID`, `userID`, `accessLevel`, `requestDate`, `approvalDate`, `approvedByUserID`, `isApproved`) VALUES (?,?,?,?,?,?,?);";
		$date = date ( 'Y-m-d H:i:s' );
		$params = array (
				$networkID,
				$userID,
				1,
				$date,
				NULL,
				NULL,
				0
		);
		$result = $this->db->query ( $sql, $params );
		return $result;
	}
	function leaveNetwork($userID, $networkID) {
		$sql = "DELETE FROM networkmembership WHERE userID= " . $userID . " AND networkID= " . $networkID . ";";
		$result = $this->db->query($sql);
		return $result;
	}
	function getApprovedFriends($user) {
		$qry = "(SELECT friendship.secondUserID as friendID ,friendship.creationDate as cDate, friendship.approvalDate as aDate,
					 users.username, users.firstName as fName, users.lastName as lName FROM
					`friendship`,users WHERE  friendship.firstUserId = ? AND
					 users.userId = friendship.secondUserId AND friendship.isApproved = 1 )
					UNION
					(SELECT friendship.firstUserID as friendID,friendship.creationDate as cDate,friendship.approvalDate as aDate,
					 users.username, users.firstName as fName, users.lastName as lName
					 FROM `friendship`,users WHERE
					 friendship.secondUserId = ? AND users.userId =friendship.firstUserId  AND friendship.isApproved = 1 )";
		
		$param = array (
				
				$user,
				$user 
		);
		
		$res = $this->db->query ( $qry, $param );
		
		return $res->result ();
	}
	function setNetworks($userId, $networkIds) {
		$sql = "INSERT INTO `networkmembership` (`networkID`, `userID`, `accessLevel`, `requestDate`, `approvalDate`, `approvedByUserID`);";
		$date = $this->db->escape ( date ( 'Y-m-d H:i:s' ) );
		
		foreach ( $networkIds as $nid ) {
			$sql += '(?,?,?,?,?,?)';
			$params = array (
					$nid,
					$userId,
					4,
					$date,
					$date,
					5 
			);
		}
		// TODO this doesn't work. You never send the query.
	}
	function getOutboundUnapprovedFriends($user) {
		$qry = "(SELECT friendship.secondUserID as friendID , friendship.creationDate as cDate,
					 users.username, users.firstName as fName, users.lastName as lName FROM
					`friendship`,users WHERE  friendship.firstUserId = ? AND
					 users.userId = friendship.secondUserId AND friendship.isApproved = 0 )
					";
		
		$param = array (
				
				$user 
		);
		
		$res = $this->db->query ( $qry, $param );
		
		return $res->result ();
	}
	function getInboundUnapprovedFriends($user) {
		$qry = "
				(SELECT friendship.firstUserID as friendID, friendship.creationDate as cDate,
				 users.username, users.firstName as fName, users.lastName as lName 
				 FROM `friendship`,users WHERE
				 friendship.secondUserId = ? AND users.userId =friendship.firstUserId  AND friendship.isApproved = 0)";
		
		$param = array (
				
				$user 
		);
		
		$res = $this->db->query ( $qry, $param );
		
		return $res->result ();
	}
	function getFriends($user) {
		$qry = "(SELECT friendship.secondUserID as friendID , friendship.creationDate as cDate,
				 users.username, users.firstName as fName, users.lastName as lName FROM 
				`friendship`,users WHERE  friendship.firstUserId = ? AND
				 users.userId =friendship.secondUserId)
				UNION
				(SELECT friendship.firstUserID as friendID,friendship.creationDate as cDate,
				 users.username, users.firstName as fName, users.lastName as lName
				 FROM `friendship`,users WHERE 
				 friendship.secondUserId = ? AND users.userId =friendship.firstUserId)";
		
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
		$sql = "INSERT INTO `globalaccessgrants` (`userId` ,`userAccessLevel` ,`grantDateTime`)
			VALUES (?, ?, ?);";
		
		$param = array (
				$userId,
				$permLevel,
				$this->db->escape ( date ( 'Y-m-d H:i:s' ) ) 
		);
		
		$this->db->query ( $sql, $param );
		return;
	}
	function getNotFriends($user) {
		$qry = "SELECT `users`.`userID` as `uid`, `users`.`firstName` as `fName`, `users`.`lastName` as `lName` , `users`.`userCreationDate` as `cDate` FROM `users`  WHERE `users`.`userID` NOT IN (SELECT firstUserId from friendship) AND `users`.`userID` NOT IN (SELECT secondUserId from friendship) AND  `users`.`userID` != ?";
		$param = array (
				$user 
		);
		$res = $this->db->query ( $qry, $param );
		
		return $res->result ();
	}
	function searchusers($qry, $cUser) {
		$baseQry = "SELECT `users`.`userID` as `uid`, `users`.`firstName` as `fName`, `users`.`lastName` as `lName` FROM `users`  WHERE `users`.`userID` != 5 AND`users`.`userID` NOT IN (SELECT firstUserId from friendship) AND `users`.`userID` NOT IN (SELECT secondUserId from friendship) AND  `users`.`userID` != 5  AND";
		
		/*
		 * if (strpos ( strip ( $qry ) ) === TRUE) { $baseQry += '`users`.`userEmail` = ?;'; $param = array ( $cUser, '%' + strip ( $sqry ) + '%' ); $res = $this->db->query ( $baseQry, $param ); return $res->result (); } $terms = explode ( " ", strip ( $qry ) ); $l = length ( $terms ); if ($l == 0) { } else if ($l == 1) { } else if ($l >= 2) )
		 */
		// Will return a list of UID in order of relevance
	}
}
?>
