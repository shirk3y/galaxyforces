<?php

if (isset($Config['Referer']) && isset($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], $Config['Referer']) === false)) {
	unset($_GET); $_GET = array();
	unset($_POST); $_POST = array();
}
