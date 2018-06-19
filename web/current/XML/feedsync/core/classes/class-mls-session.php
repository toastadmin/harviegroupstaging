<?php
include_once(CORE_PATH.'classes/mls/vendor/autoload.php');

use \PHRETS\Session;

class MLS_SESSION extends \PHRETS\Session{

	function __construct($mls_config) {

		parent::__construct($mls_config);
	}

	
}
