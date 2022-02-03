<?php

require_once('/opt/kwynn/kwutils.php');

function didAnyCallMe($fin) {
	if (didCLICallMe($fin)) return TRUE;
	if ( iscli()) return FALSE;
	if (basename(__FILE__) === basename($_SERVER['REQUEST_URI'])) return TRUE;
	return FALSE;
}

function tdacm() {
	echo('did call me?' . "\n");
	echo((didAnyCallMe(__FILE__) ? 'Y' : 'N') . "\n");
}

tdacm();
