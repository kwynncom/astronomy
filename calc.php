<?php

require_once('data.php');

class moon_calc implements moon_config {

	const huddfs = 'D M d';
	
	public function __construct() {
		$this->phca = [];
		$r30 = moon_data::get();
		$this->do40($r30);
		return;
	}
	
    public static function get() {
		$o = new self();
		return $o->getI();
	}
	
	public function getI() {
		$ra = ['cala' => $this->cala, 'phcha' => $this->phcha];
		$j  = json_encode($ra);
		return $j;
	}

	function do40($ala) {

		static $minMax =  self::safePhD * DAY_S;
		
		$d10 = new DateTime();
		$d10->setTimestamp($ala[0]['U']);
		$d20 = new DateTime($d10->format('Y-m-d 23:59:59.999999')); unset($d10);
		$now = time();
		
		
		for ($i=1; $i <= self::calcDays; $i++) {
			
			$d20ts = $d20->getTimestamp();
			
			if ($d20ts > $ala[0]['U']) {
				$ta = array_shift($ala);
				$ta['pd'] = 1;
				$this->cl10($ta); 
				$d = $now - $ta['U'];
				if (   ($d > 0 && $d <  $minMax) 
					|| ($d < 0 && $d > -$minMax) )
					$this->phcha[] = $ta; unset($d);
			} else {
				$ta['pd']++;
				$this->cl10($ta);
			}

			$hud = $ta['hud'] = $d20->format(self::huddfs);
			if ($d20ts >= $now) $r[] = $ta; unset($d20ts);
			$d20->add(new DateInterval('P1D'));
			continue;
		} unset($ta, $d20, $i, $ala, $now, $minMax, $d20ts);
		
		$this->cala = $r; unset($r);
		
		return;
	}
	
	function cl10(&$ar) {
		
		$ak  = ['n', 't', 'pd', 'isq'];
		$pkt = ['z', 'U', 'r', 'hut', 'hud'];
		$pk = kwam($ak, $pkt); unset($pkt);
		
		$tar = $ar;
		
		foreach($tar as $f => $ignore) {
			if ($tar['pd'] !== 1) { if (!in_array($f, $ak)) unset($ar[$f]); }
			else  {					if (!in_array($f, $pk)) unset($ar[$f]); 
				$ar['hut'] = date('g:i A', $ar['U']);	
				$ar['ms' ] = $ar['U'] * 1000;
			}
		}

		return $ar;
	}
}