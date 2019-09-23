<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<!DOCTYPE html>
<html>
<head>
	<title>SDMIT PLACEMENT</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Add icon library -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		.btn {
			background-color: DodgerBlue;
			border: none;
			color: white;
			padding: 12px 30px;
			cursor: pointer;
			font-size: 20px;
		}

		/* Darker background on mouse-over */
		.btn:hover {
			background-color: RoyalBlue;
		}
	</style>
</head>
<body>

<div align="center">
	<font color="#a52a2a">
<h1>SDMIT PLACEMENT</h1></font>
</div>

<div align="center">
	<font color="#006400">
		<h3>Click On The Link Below To Download The App</h3></font>
	<a href="<?php echo base_url()?>home/download/sdmitplacement.apk">
	<button class="btn"><i class="fa fa-download"></i> Download</button>
	</a>
</div>


</body>
</html>

