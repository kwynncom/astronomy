<?php

require_once('/opt/kwynn/kwutils.php');
require_once('config.php');

class moon_data extends dao_generic_3 implements moon_config { 
	
	const dbname = 'moon';

	public static function get() {
		$o = new self();
		return $o->getI();
	}
	
	private function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs(['m' => 'moon']);
		$this->mcoll->createIndex(['U' => 1], ['unique' => true]);
		$this->phca = [];
		$this->do10();
		return;
	}

	public function getI() {
		$now = time();
		$min = $now - DAY_S *  self::safePhD;
		$max = $now + DAY_S * (self::safePhD + self::calcDays);
		$res = $this->mcoll->find(['$and' => [['U' => ['$gte' => $min]], ['U' => ['$lt' => $max]]]]);
		return $res;
	}
	
	function already() {
		$now = time();
		if (!$this->mcoll->findOne(['U' => ['$lte' => $now - DAY_S * self::safePhD]]))		 return false;
		if (!$this->mcoll->findOne(['U' => ['$gte' => $now + DAY_S * self::newAlmanacIfDays]])) return false;
		return true;
	}
	
	function do10() {
		if ($this->already()) return;
		return $this->do20(trim(shell_exec('/usr/bin/python3 ' . __DIR__ . '/moon.py')));
	}
	
	function do20($t) {
		$aa = explode("\n", $t); unset($t);
		kwas(count($aa) === 3, 'moon bad count 0020');
		$ms = [];
		foreach([0,1] as $i) preg_match_all("/'([^']+)'/", $aa[$i], $ms[$i]); unset($i);
		$a['z'] = $ms[0][1];
		$a['t'] = $ms[1][1];
		preg_match_all('/\d/', $aa[2], $ms[2]); unset($aa);
		$a['n'] = $ms[2][0]; unset($ms);
		$r = [];
		foreach($a['z'] as $i => $z) {
			$U = strtotime($z);
			$n  = intval($a['n'][$i]);
			$t = $a['t'][$i];
			$r = date('r', $U);
			$_id =  $z . '-mph-' . $n;
			$dat = get_defined_vars(); 
			unset($dat['a'], $dat['i']);
			$this->mcoll->upsert(['_id' => $_id], $dat, 1, false); unset($U, $dat, $n, $r, $t, $z, $_id);
		} 	unset($i, $p, $a);
	}
	
	public static function ppyarr($s) {
		return str_replace("'", '"', $s) . ';';
		
	}
}

if (didCLICallMe(__FILE__)) new moon_data();
