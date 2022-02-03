<?php

require_once('/opt/kwynn/kwutils.php');

interface moon_config  { 
	const safePhD  = 9;
	const calcDays = 45;
	const maxDaysNeeded = self::safePhD + self::calcDays;
	const extraDays = 50;
	const newAlmanacIfDays = self::maxDaysNeeded + self::extraDays;
}
