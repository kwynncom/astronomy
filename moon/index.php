<!DOCTYPE html>
<html lang='en'><?php require_once('calc.php'); ?>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>moon phase</title>

<style>
body { font-family: sans-serif; }
td   { font-family: monospace; }
.mocl10 {
    width     : 2.8ex;
    text-align : center;
    display   : inline-block;
    padding: 0.5ex;
}

table {   border-collapse: separate; }

.per20p { padding-right: 1ex; }
</style>

<script src='/opt/kwynn/js/utils.js'></script>
<script>

function onGotData(monabig) {   		
	new moonCal (monabig.cala);
	new lunation(monabig.phcha);
	kwjss.sobf('getData.php');
} 

kwjss.sobf('calc.php', {'tzName' : tzName() }, onGotData);

class lunation {
    
    config() {
        this.e10 = byid('per10');
		this.e20 = byid('per20');
        this.dinterval = 150;
        this.decright = 8;
    }
    
    constructor(ain) {
		this.thea = ain;
        this.config();
        this.onInt();
		inht('tzrce', tzName());
        this.setInt();
    }
	
	calc() {
		const a = this.thea;
		const l = a.length;
		let bi = 0;
		let ai = 1;
		const t = time();
		
		while (		(!(      t >= a[bi]['ms']
						  && t <  a[ai]['ms'] ))
				&&					ai < 30 // prevent possible infinite loop
				) {bi++;			ai++; }
			
		const span = a[ai]['ms'] - a[bi]['ms'];
		const prog = t -		   a[bi]['ms'];
		const perc = prog / span;
		const phas = a[bi]['n'];
		const v100 = (phas + perc) * 0.25;
		return v100;
	} 

    onInt() {
        const p = this.calc();
        const pd = p.toFixed(this.decright);
        this.e10.innerHTML = pd;
		let raf = p * 24;
		if (raf > 12) raf = 24 - raf;
		inht(this.e20, raf.toFixed(1));
    }

    setInt() {
        const self = this;
        setInterval(() => { self.onInt(); }, this.dinterval);       
    }
}

class moonCal {
    constructor(cala) {
		let v;
        for (let i=0; v = cala[i]; i++) {
            const tr = cree('tr');
            this.do22(tr, v);
            byid('tbody10').append(tr);
      }	
	}

	do22(tr, ain) {
		
		const tdd = cree('td');
		tdd.innerHTML = ain.hud;
		tr.append(tdd);
		
        const td = cree('td');
        td.style.backgroundColor = 'black';
        td.style.border = 'black';
        const span = cree('span');
        
        span.className = 'mocl10';
        
        const span20 = cree('span');
        span.append(span20);
        span20.innerHTML =  this.getuni(ain);;
        span20.className = 'cspan';
            
        span20.style.opacity = this.getOpacity(ain);
        td.append(span);
        tr.append(td);
		
		const tdl = cree('td');
		if (ain.t && ain.pd === 1) inht(tdl, ain.hut + ' ' + ain.t);
		tr.append(tdl);
    }
 
	getuni(ain) {
		if (ain.n === 0 && ain.pd === 1) return '&#127761;';
		if (ain.n === 0)				 return '&#127762;';
		if (ain.n === 1)				 return '&#127764;';
		if (ain.n === 2 && ain.pd === 1) return '&#127765;';
		if (ain.n === 2)				 return '&#127766;';
		if (ain.n === 3)				 return '&#127768;';
	}
 
    getOpacity(ain) {
		let pd = ain.pd;
		const n = ain.n;
		
        if (n >= 2) pd = 9 - pd; // reverse for waning

		if (n === 0 && pd === 1) return 0.35; // new

        if (n === 0 || n === 3) switch(pd) { // crescent
            case 1 : return 0.25;
            case 2 : return 0.35;
            case 3 : return 0.55;
            case 4 : return 0.58;
            case 5 : return 0.65;
            case 6 : return 0.78;
        }
  
       if (n === 1 || n === 2) switch(pd) { // gibbous
            case 1 : return 0.70;
            case 2 : return 0.75;
            case 3 : return 0.83;
            case 4 : return 0.88;
            case 5 : return 0.93;
         }
        
        return 1;
    }
}
</script>

</head>
<body>
    <p>
        <a href='/'>home</a>
        <a    style='padding-left: 5ex;' href='/t/6/07/ql/quick_links.html'>ql</a>
        <a    style='padding-left: 5ex;' href='https://www.timeanddate.com/moon/usa/atlanta'>T&D</a>
		<span style='padding-left: 2ex;'>more info at bottom</span>
    </p>
    
    <div>
		<p>
			<span class='per20p'><span id='per20'></span> RAo</span>
			<span id='per10'></span>
			<label>TZ</label>
			<span id='tzrce'></span>
	</p>
    <table>
        <tbody id='tbody10'>
        </tbody>
    </table>
	</div>
	<div>

	<p>The constantly running number is the fraction of the moon's lunation (lunar month) where 0 is new and 0.5 is full and 0.99 is almost new again.  
		"TZ" shows the timezone reference city / region, which should be your timezone / your browser's timezone / your OS's timezone / your device's TZ.  
		For example, America/New_York is the reference city for US Eastern Time (ET / EST / EDT).  See WikiP's 
		<a href='https://en.wikipedia.org/wiki/List_of_tz_database_time_zones'>TZ list</a>.  
	</p>
		
	<p>The hours RA (right ascension) offset is that between the moon and sun as seen from earth, where 24 hours right ascension is the entire 360&deg; 
		of the sky (every hour RA of sky is 15&deg; of sky).  A full moon is 12 hours or 180&deg; apart between sun and moon.  I show the RA up to 12 for a 
		waxing moon and then start from 12 down to 0 for a waning moon, rather than 24 hours.
	</p>
	
	<p>Otherwise put, the RA offset is the lunation fraction above times 24.  In my case, I subtract from 24 if over 12.
		
	</p>


	
	<p><a href='https://github.com/kwynncom/astronomy'>source code</a>	</p>
	
	</div>
	
<div class='htvd'>
    <a href="https://validator.w3.org/check?uri=https://kwynn.com/t/22/01/moon/"><img
        src="/t/5/02/html5_valid.jpg"
        alt="HTML5 validation check" width="103" height="36" /></a>
</div>
</body>
</html>
