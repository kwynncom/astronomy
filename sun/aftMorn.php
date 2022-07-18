<?php

function aftMorn($nts) {
    $now = time();
    
    $hr = intval(date('H', $now));
    if ($hr < 8 || $hr >= 15) return '';
    
    $ht  = '';
    $ht .= '<p>';
    $ht .= 'Good ';
    
    if ($hr < 12) $ht .= 'morning';
    else          $ht .= 'afternoon';
    
    $ht .= ' and ';
    
    
    
}