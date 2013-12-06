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
}