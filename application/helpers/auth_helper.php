<?php
function checkAuth($context) {
	if ($context->session->userdata ( 'logged_in' )) {
		return true;
	} else {
		return false;
	}
}




?>