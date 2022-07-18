var KWYNN_SUN_TIMES_TDEND_A  = false;
var KWYNN_SUN_TIMES_DIFFTSMS = false;


window.onload = function() { 
   
    doSecondTick();
    
    const dob = new Date();
    const ms  = dob.getTime();
    const uns = 1000 - (ms - ((Math.floor(ms / 1000) * 1000)));

    setTimeout(function() {      doSecondTick(); setInterval(doSecondTick, 1000);   }, uns);
}

function doSecondTick() {
    const mytob = getTime();
    const ts = mytob['ts'];
    let pi = 0;
    
    aftMorn(mytob);
      
    KWYNN_SUN_TIMES_TDEND_A.forEach(function(v, i) {
	if (ts - 999 > v) {
	    pi = i + 1;
	    return;
	}
    });
    
    if (pi >= 0 && pi < KWYNN_SUN_TIMES_TDEND_A.length) {
	document.getElementById('td' + pi).innerHTML = mytob['s'];	
	if (pi >= 1)
	document.getElementById('td' + (pi - 1)).innerHTML = '';    
    } else {
	document.getElementById('td' + (KWYNN_SUN_TIMES_TDEND_A.length - 1)).innerHTML = '';
    }
    
    
    document.getElementById('defaultclock').innerHTML = mytob['s'];
    

    
}

function getTime(){
    const nowts  = Date.now();
    const showts = nowts - KWYNN_SUN_TIMES_DIFFTSMS;
    const date   = new Date(showts);
    let   h = date.getHours();
    const h24 = h;
    let m = date.getMinutes(); 
    let s = date.getSeconds(); 
    let ampm = 'AM';

    if (h24 >= 12) ampm = 'PM'; // 2020/03/10 1:15pm
    
    if(h == 0) h = 12;
    if(h > 12) h = h - 12;

    m = (m < 10) ? '0' + m : m;
    s = (s < 10) ? '0' + s : s;
    let time = h + ':' + m + ':' + s;
    let rob = {};
    rob['ts'] = showts;
    rob['s'] = time;
    rob['ampm'] = ampm;
    rob['H'] = h24;
    return rob;
} // starting from https://codepen.io/afarrar/pen/JRaEjP by Aaron Farrar


