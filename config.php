<?php

require_once('/opt/kwynn/kwutils.php');

interface moon_config  { 
	const safePhD  = 10;
	const minCalcDays = 45;
	const calcDays = self::minCalcDays + self::safePhD;
	const extraDays = 50;
}
