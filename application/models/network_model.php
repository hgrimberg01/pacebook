<?php
class Network_model extends CI_Model {
	function __construct() {
		// Call the Model constructor
		parent::__construct ();
	}
	function getAllNetworks() {
		$sql = "SELECT networkName,networkID from networks;";
		$res = $this->db->query ( $sql );
		return $res->result ();
	}
	function putNetwork($nName, $nDesc, $nProfileImgID) {
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
					NULL, ". $cleaned_nName .", " . $cleaned_desc . ", " . $datetime . ",
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
		return $res->result()[0]->members;
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
	function setApprovalState($networkID, $approved, $note) {
		if ($approved == true) {
			// approve network
			
			$approve_qry = "UPDATE `networks` SET `networkIsActive` = 1, approvalDate = ? , note = ? , WHERE networkID = ? ;";
			$param = array (
				$this->db->escape ( date ( 'Y-m-d H:i:s')),
				$this->db->escape ($note),
				$networkID
			);
			
			$res = $this->db->query($qry, $param);
		} else {
			// de-approve network
			
			$disapprove_qry = "UPDATE `networks` SET `networkIsActive` = 0, approvalDate = NULL, note = ? , WHERE networkID = ? ;";
			$param = array (
				$this->db->escape($note),
				$networkID
			);
			
			$res = $this->db->query($qry, $param);
		}
	}
}