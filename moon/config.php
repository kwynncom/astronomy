<?php

require_once('/opt/kwynn/kwutils.php');

interface moon_config  { 
	const safePhD  = 10;
	const displayDays = 45;
	const calcDays = self::displayDays + self::safePhD * 2;
	const extraDays = 50;
}
