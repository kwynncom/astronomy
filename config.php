<?php

require_once('/opt/kwynn/kwutils.php');

interface moon_config  { 
	const safePhD  = 10;
	const calcDays = 45;
	const maxDaysNeeded = self::safePhD + self::calcDays;
	const extraDays = 50;
}
