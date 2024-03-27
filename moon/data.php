<?php

require_once('/opt/kwynn/kwutils.php');
require_once('config.php');

class moon_data extends dao_generic_3 implements moon_config { 
	
	const dbname = 'moon';

	public static function get($edin = 0) {
		$o = new self($edin);
		return $o->getI();
	}
	
	private function __construct($edin = 0) {
		$this->minDays = self::safePhD;
		$this->maxDays = self::calcDays + $edin; unset($edin);
		parent::__construct(self::dbname);
		$this->creTabs(['m' => 'moon']);
		$this->mcoll->createIndex(['U' =>  1], ['unique' => true]);
		$this->mcoll->createIndex(['U' => -1], ['unique' => true]);
		// $this->phca = [];
		$this->now = time();
		$this->getAlmanacIf();
		return;
	}

	private static function emp($in) {
		file_put_contents('/tmp/em1.txt', json_encode($in), FILE_APPEND);
	}
	
	public function getI() {
		$qmin = $this->getMinQ(true);
		$qmax = $this->getMaxQ(false);
		$q    = ['$and' => [$qmin, $qmax]];
		
		self::emp($q);
		$res = $this->mcoll->find($q, ['sort' => ['U' => 1]]);
		self::emp($res);
		return $res;
	}
	
	private function getMinQ($uslev = false) { 
		$q10 = ['U' => ['$gte' => $this->now - DAY_S * $this->minDays]];
		if ($uslev) return $q10;
		$q20 = ['U' => ['$lte' => $this->now]];
		$q30 = ['$and' => [$q10, $q20]];
		return $q30;
	}
	
	private function getMaxQ($ckAlready = false) {
		$p10 = $this->now + DAY_S * $this->maxDays;
		
		if ($ckAlready) $op = '$gte';
		else			$op = '$lte';
		return ['U' => [$op => $p10]];
	}
	
	private function already() {
		if (!$this->mcoll->findOne($this->getMinQ( ))) return false;
		if (!$this->mcoll->findOne($this->getMaxQ(1))) return false;
		return true;
	}
	
	private function getAlmanacIf() {
		if ($this->already()) return;
		$cmd = 'python3 ' . __DIR__ . '/moon.py' . ' ' . ($this->minDays) . ' ' . ($this->maxDays);
		$res = shell_exec($cmd);
		return $this->processAlmanac(trim($res));
	}
	
	private function processAlmanac($t) {
		$aa = json_decode($t, true); unset($t);
		kwas(count($aa) === 3, 'moon bad count 0020');
		$a['z'] = $aa[0];
		$a['t'] = $aa[1];
		$a['n'] = $aa[2]; unset($aa);
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
}

if (didCLICallMe(__FILE__)) moon_data::get(moon_config::extraDays);
