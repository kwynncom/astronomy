function aftMorn(o) {
    let g = '';
    
    if (o.H < 8 || o.H >= 16) {
	byid('aftMorn').style = 'display: none';
	return;
    } else byid('aftMorn').style = 'display: block';
    
    g += 'Good ';
    if (o.ampm === 'AM') g += 'morning';
    else		 g += 'afternoon';
    
    g += ' and ';
    
    if (o.ts >= KWYNN_SUN_SOLAR_NOON_TS) g += 'afternoon';
    else				 g += 'morning';
    
    g += '!';
    
    
    byid('aftMorn').innerHTML = g;
    
    
    
}