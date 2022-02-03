<?php

require_once('/opt/kwynn/kwutils.php');

if (didAnyCallMe(__FILE__)) {
	require_once('data.php');
	moon_data::get(moon_config::extraDays);
}
