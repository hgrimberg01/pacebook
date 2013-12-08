<?php
class Network_model extends CI_Model {
	function __construct() {
		// Call the Model constructor
		parent::__construct ();
	}
	function getNetwork($networkID) {
		$sql = "SELECT networkName, networkDesc, networkCreationDate FROM networks WHERE networkID = ? ;";
		$qry = $this->db->query($sql, array($networkID));
		$result = $qry->result()[0];
		// add count information
		// TODO what if no rows are returned?
		$numMembers = $this->getNetworkMemberCount($networkID);
		$result['members'] = $numMembers;
		return $result;
	}
	function getNetworks($networkID_array) {
		// return information on an array of networks
		if (empty($networkID_array)) {
			return array();
		}
		$a_map = array_map(function($obj) { return $obj->ID;}, $networkID_array);
		$list = str_replace("'", "", implode(", ", $a_map));
		$sql = "SELECT networks.networkID, networkName AS name, networkDesc, networkCreationDate AS cDate, numMembers FROM networks
				JOIN (SELECT networkID, COUNT(*) AS numMembers FROM networkmembership WHERE networkID IN ( ".$list." ) AND isApproved=1 GROUP BY networkID) AS Counts
				ON Counts.networkID=networks.networkID WHERE networkIsActive=1;";
		// note: do not have to account for networks that do not show up in networkmembership because no user could be a member of them
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	function getManagedNetworks($user_id) {
		// returns all the active networks managed by that user
		$sql = "SELECT networks.networkID AS nid, networkName AS name, networkDesc AS descr, networkCreationDate AS cDate, numMembers
				FROM networks, networkmembership AS mem, 
				(SELECT networkID, COUNT(*) AS numMembers FROM networkmembership WHERE isApproved=1 GROUP BY networkID) AS Counts
				WHERE networkIsActive=1
					AND mem.networkID = networks.networkID
					AND Counts.networkID = networks.networkID
					AND mem.userID = " . $user_id . "
					AND mem.accessLevel=4;";
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	function getManagedJoinRequests($user_id) {
		// returns all of the join requests for groups managed by that user
		$sql = "SELECT networks.networkID AS nid, networkName AS name, users.userID AS uid, users.userName AS appName
				FROM networks, networkmembership AS own, networkmembership AS mem, users
				WHERE networkIsActive=1
					AND own.networkID = networks.networkID
					AND own.userID = " . $user_id . "
					AND own.accessLevel=4
					AND mem.networkID = networks.networkID
					AND mem.isApproved=0
					AND mem.approvedByUserID IS NULL
					AND mem.userID = users.userID;";
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	function getNetworksNotIn($networkID_array) {
		if (empty($networkID_array)) {
			return $this->getAllNetworksDetailed();
		}
		$a_map = array_map(function($obj) { return $obj->ID;}, $networkID_array);
		$list = str_replace("'", "", implode(", ", $a_map));
		$sql = "SELECT networks.networkID AS nid, networkName AS name, networkDesc AS descr, numMembers, users.userName AS owner
				FROM networks, networkmembership AS netOwns, users, 
				(SELECT networkID, COUNT(*) AS numMembers FROM networkmembership WHERE networkID NOT IN ( ".$list." ) AND isApproved=1 GROUP BY networkID) AS Counts
				WHERE networkIsActive=1
					AND Counts.networkID = networks.networkID
					AND netOwns.networkID=networks.networkID AND netOwns.accessLevel=4
					AND users.userID=netOwns.userID;";
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	function getPendingNetworkJoins($networkID_array, $userID) {
		// return information on pending network join requests
		
		if (empty($networkID_array)) {
			return array();
		}
		$a_map = array_map(function($obj) { return $obj->ID;}, $networkID_array);
		$list = str_replace("'", "", implode(", ", $a_map));
		$sql = "SELECT networks.networkID, networkName AS name, requestDate AS reqDate FROM networks, networkmembership
				WHERE networks.networkID IN ( " . $list . " ) AND networks.networkID=networkmembership.networkID AND networkmembership.userID=" . $userID . ";";
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	function getPendingNetworkApprovals($networkID_array) {
		if (empty($networkID_array)) {
			return array();
		}
		$a_map = array_map(function($obj) { return $obj->ID;}, $networkID_array);
		$list = str_replace("'", "", implode(", ", $a_map));
		$sql = "SELECT networkID, networkName AS name, networkCreationDate AS cDate, networkApprovedByUserID AS approver
				FROM networks
				WHERE networkID IN ( " . $list . " ) AND networkIsActive=0;";
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	function getAllPendingApprovals() {
		$sql = "SELECT networks.networkID AS nid, networkName AS name, networkDesc AS descr, networkCreationDate AS cDate, users.userName AS owner
				FROM networks, networkmembership AS mem, users
				WHERE networkIsActive=0 AND networks.networkApprovedByUserID IS NULL
					AND mem.networkID = networks.networkID
					AND mem.userID = users.userID
					AND mem.accessLevel=4;";
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	function getAllNetworks() {
		$sql = "SELECT networkName,networkID from networks;";
		$res = $this->db->query ( $sql );
		return $res->result ();
	}
	function getAllNetworksDetailed() {
		$sql = "SELECT networks.networkID AS nid, networkName AS name, networkDesc AS descr, numMembers, users.userName AS owner
				FROM networks, networkmembership AS netOwns, users,
				(SELECT networkID, COUNT(*) AS numMembers FROM networkmembership GROUP BY networkID) AS Counts
				WHERE networkIsActive=1
					AND Counts.networkID = networks.networkID
					AND netOwns.networkID=networks.networkID AND netOwns.accessLevel=4
					AND users.userID=netOwns.userID;";
		$qry = $this->db->query($sql);
		$result = $qry->result();
		return $result;
	}
	function putNetwork($nName, $nDesc) {
		$cleaned_nName = $this->db->escape($nName);
		$check_network_exists_qry = 'SELECT networkName FROM Networks WHERE networkName = ' . $cleaned_nName . ';';
		
		$query = $this->db->query($check_network_exists_qry);
		if ($query->num_rows() > 0) {
			throw new Exception( 'NETWORK_EXISTS' );
		} else {

			$cleaned_desc = $this->db->escape($nDesc);
			// no support for profile images currently exists
			$dateTime = $this->db->escape( date('Y-m-d H:i:s'));
			
			$new_database_qry = "INSERT INTO `qchen`.`Networks` (
					`networkID`, `networkName`, `networkDesc`, `networkCreationDate`,
					`networkProfileImgID`, `networkIsActive`, `networkApprovalDate`,
					`networkApprovedByUserID`, `Note`)
					VALUES (
					NULL, ". $cleaned_nName .", " . $cleaned_desc . ", " . $dateTime . ",
					NULL, 0, NULL,
					NULL, NULL)
					";
			
			$retn = $this->db->query( $new_database_qry);
			
			return $this->db->insert_id();
		}
	}
	function deleteNetwork($networkID) {
		// delete a network
		$sql = 'DELETE FROM networks WHERE networkID = ' . $networkID . ';';
		
		$res = $this->db->query ($sql);
	}
	function updateNetwork($networkID, $nName, $nDesc) {
		// change the name or description

		// check that name doesn't already exist for a different network
		$cleaned_nName = $this->db->escape($nName);
		$check_exists_qry = 'SELECT networkID FROM networks WHERE networkName = ' . $cleaned_nName . ' AND networkID <> ' . $networkID . ';';
		
		$query = $this->db->query($check_exists_qry);
		if ($query->num_rows() > 0) {
			throw new Exception( 'NETWORK_EXISTS' );
		} else {
			$sql = "UPDATE `networks` SET networkName = ? , networkDesc = ? WHERE networkID = ? ;";
			$param = array (
				$cleaned_nName,
				$this->db->escape($nDesc),
				$networkID
			);
		}
	}
	function getTotalCount() {
		// total number of networks
		$sql = "SELECT COUNT(*) AS total FROM networks;";
		$res = $this->db->query ($sql);
		return $res->result()[0]->total;
	}
	function getActiveCount() {
		// total active networks
		$sql = "SELECT COUNT(*) AS total FROM networks WHERE networkIsActive=1";
		$res = $this->db->query($sql);
		return $res->result()[0]->total;
	}
	function getPendingCount() {
		// total networks pending approval
		$sql = "SELECT COUNT(*) AS total FROM networks WHERE networkIsActive=0";
		$res = $this->db->query($sql);
		return $res->result()[0]->total;
	}
	function getNetworkMemberCount($networkID) {
		// number of members in a network
		$sql = 'SELECT COUNT(*) AS members FROM networkmembership WHERE networkID = ' . $networkID . ';';
		$query = $this->db->query($sql);
		return $query->result()[0]->members;
	}
	function getNetworkMemberCounts($networkIDs) {
		$sql = 'SELECT networkID, COUNT(*) AS members FROM networkmembership GROUP BY networkID WHERE networkID IN ( ? )';
		$query = $this->db->query($sql);// TODO fix this
		return $query->result();
	}
	function isApproved($networkID) {
		// checks whether a network with the given name is approved
		$check_approved_qry = 'SELECT networkName FROM Networks WHERE networkID = ' . $networkID . ' AND networkIsActive=1;';
		
		$query = $this->db->query($check_approved_qry);
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	function setApprovalState($networkID, $approved, $note, $approver) {
		if ($approved == true) {
			// approve network
			
			$approve_qry = "UPDATE `networks` SET `networkIsActive` = 1, networkApprovalDate = ? , note = ? , networkApprovedByUserID = ?  WHERE networkID = ? ;";
			$param = array (
				$this->db->escape ( date ( 'Y-m-d H:i:s')),
				$this->db->escape ($note),
				$approver,
				$networkID
			);
			
			$res = $this->db->query($approve_qry, $param);
		} else {
			// de-approve network
			
			$disapprove_qry = "UPDATE `networks` SET `networkIsActive` = 0, networkApprovalDate = NULL, note = ? , networkApprovedByUserID = ?  WHERE networkID = ? ;";
			$param = array (
				$this->db->escape($note),
				$approver,
				$networkID
			);
			
			$res = $this->db->query($disapprove_qry, $param);
		}
		return $res;
	}
	function setJoinState($networkID, $applicantID, $approved, $approver) {
		if ($approved == true) {
			// approve network
				
			$approve_qry = "UPDATE networkmembership SET isApproved = 1, approvalDate = ? , approvedByUserID = ?  WHERE networkID = ? AND userID = ? ;";
			$param = array (
					date ( 'Y-m-d H:i:s'),
					$approver,
					$networkID,
					$applicantID
			);
				
			$res = $this->db->query($approve_qry, $param);
		} else {
			// de-approve network
				
			$disapprove_qry = "UPDATE networkmembership SET isApproved = 0, approvalDate = NULL, approvedByUserID = ?  WHERE networkID = ? AND userID = ? ;";
			$param = array (
					$approver,
					$networkID,
					$applicantID
			);
				
			$res = $this->db->query($disapprove_qry, $param);
		}
		return $res;
	}
}