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
		$this->maxDays = self::safePhD + self::calcDays + $edin; unset($edin);
		parent::__construct(self::dbname);
		$this->creTabs(['m' => 'moon']);
		$this->mcoll->createIndex(['U' =>  1], ['unique' => true]);
		$this->mcoll->createIndex(['U' => -1], ['unique' => true]);
		$this->phca = [];
		$this->now = time();
		$this->getAlmanacIf();
		return;
	}

	public function getI() {
		$res = $this->mcoll->find($this->getMaxQ());
		return $res;
	}
	
	private function getMinQ() { 
		$q10 = ['U' => ['$gte' => $this->now - DAY_S * $this->minDays]];
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
		return $this->processAlmanac(trim(shell_exec('python3 ' . __DIR__ . '/moon.py' . ' ' . $this->minDays . ' ' . ($this->maxDays + 2))));
	}
	
	private function processAlmanac($t) {
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
}

if (didCLICallMe(__FILE__)) moon_data::get(moon_config::extraDays);
