<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>sun info</title>

<script src='utils.js'></script>
<script src='aftMorn.js'></script>
<script src='js.js'></script>


<style>
    body { font-family: monospace; font-size: 117% }
    td   { text-align: right }
    #defaultclock { font-size: 130% }
    </style>

</head>
<body>
    <?php require_once('out.php');
	  require_once('aftMorn.php');
	  $so = getSunStatus();   ?>
    
    <p id='aftMorn' />
    
    <div id='defaultclock' style='display: block'></div>
    <table>
	<?php echo $so['ht']; ?>
    </table>
    
	<p><a href='/t/8/02/sun/sunset.php'>older 2018 version</a> with some more notes
		
	</p>
</body>
</html>
