<?php

require_once('/opt/kwynn/kwutils.php');

if (PHP_SAPI === 'cli') getSunStatus();

function getSunStatus() {
    
    $loc = ['lat' => 34.249685, 'lon' =>  -84.140483, 'name' => 'Sawnee Mountain Preserve, Cumming, GA, USA']; 
    
    $now = time();
    
    if (1 || isAWS())
    $forts = $now;
    else 
    $forts = strtotime('2019-12-06 00:00');
    $gmto = -5 + date('I', $forts);

    $alb = ['atm' => 'astronomical_twilight_begin', 'ntm' => 'nautical_twilight_begin', 'ctm' => 'civil_twilight_begin', 
		'rise' => 'sunrise', 'noon' => 'transit', 'set' => 'sunset', 'cte' => 'civil_twilight_end', 
		'nte' => 'nautical_twilight_end', 'ate' => 'astronomical_twilight_end'];
    // $azs = [108, 102, 96, 90.833333];
    
    $hht  = '';
    $hht .= '<tr>';
    foreach($alb as $l => $ignore) $hht .= "<th>$l</th>";
    $hht .= '</tr>' . "\n";
    $mht  = '<tr>';
    $cht2 = '<tr>';
    
	$timea = date_sun_info($forts, $loc['lat'], $loc['lon']);
	
    $tss = [];
    $cnt = 0;

	foreach($alb as $mylabel => $sikey)
    {
		$f = 'g:i';
		if ($cnt === 3 || $cnt === 4 || $cnt === 5) $f = 'g:i:s';	

		$ts =  $timea[$sikey];
		$s = date($f, $ts);
		
		if ($mylabel === 'noon') $nts = $ts;
	
		// else continue; // *************
	
		$tssms[$cnt] = $ts * 1000;

		$cht2 .= "<td id='td$cnt'></td>";

		$mht .= '<td>';
		$mht .= $s;
		$mht .= '</td>';

		$cnt++;
    }
    
    $mht   .= '</tr>' . "\n";
    $cht2 .= '</tr>' . "\n";
    
    $cht = '';
    $pht = '';
    
    $json = json_encode($tssms);
    
    $diffms = ($now - $forts) * 1000;
    
    $js = <<<JS1
	    <script>
	      KWYNN_SUN_TIMES_TDEND_A = JSON.parse('$json');
	      KWYNN_SUN_TIMES_DIFFTSMS = $diffms;
	      KWYNN_SUN_SOLAR_NOON_TS = $nts * 1000;
	    </script>
JS1;
    
    $rht = $js . $pht . $hht . $cht . $cht2 . $mht;

    return ['ht' => $rht, 'nts' => $nts];
 
}
