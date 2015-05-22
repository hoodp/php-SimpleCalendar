<?php

	// get calendar month from url string or set to current month
	$month = isset($_GET['month']) ? $_GET['month'] : date('M');

	// get year from query string or set to current year
	$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

	// cookie that stores background color
	$cookie_bkg = "backgroundColor";

	// set background color cookie if it doesnt exist or color was updated
	if (!isset($_COOKIE[$cookie_bkg]) || isset($_POST['color'])) {
	   
	   // set background to white is new color is not selected
	   $bkg_color = isset($_POST['color']) ? $_POST['color'] : 'white';

	   // set cookie to expire after one day
	   setcookie($cookie_bkg, $bkg_color, time() + 86400, "/");
	}
	
	// cookie that stores font size
	$cookie_fntSize = "fontSize";

	// set font size cookie if it doesn't exist or was updated
	if (!isset($_COOKIE[$cookie_fntSize]) || isset($_POST['size'])) {
	  
	  // set default size to 16 if size is not set
	  $fontSize = isset($_POST['size']) ? $_POST['size'] : '16';

	  // add px to end of string
	  $fontSize .= 'px';
	  setcookie($cookie_fntSize, $fontSize, time() + 86400, "/");
	}
?>	
<!DOCTYPE html>
<html>
<head>
	<title>PHP Calendar</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<script>
window.onload = function() {
	var html = document.getElementsByTagName("html")[0];

	// get cookies & remove whitespace & split by ; character
	var cookies = document.cookie.replace(" ", "").split(";");

	// start looping through each cookie and setting attribute to html body
	for (var i = 0; i < cookies.length; i++) {
	    
	    // split the cookie by = character, first index is attribute, second is value
	    var cookie = cookies[i].split("=");
	    html.style[cookie[0]] = cookie[1];

	    // update table font as well
	    if (cookie[0] === "fontSize")
	       document.getElementsByTagName("table")[0].style["fontSize"] = cookie[1];
	}

	// update slider value
	document.getElementsByName("size")[0].value = parseInt(html.style["fontSize"]);
	updateSize();
};

	// updates font size text after slider moves
	function updateSize() {
		 
		 // get font size and add px at end of the string
		 var value = document.getElementsByName("size")[0].value + "px";
		 document.getElementById("fontSize").innerText = value;
	}
</script>
<body>
<h1>PHP Calendar</h1>
<?php
	
	// array containing all days of the week in order
	$days = array(
	      'Sunday',
	      'Monday',
	      'Tuesday',
	      'Wednesday',
	      'Thursday',
	      'Friday',
	      'Saturday'
	);
	date_default_timezone_set('America/New_York');
	echo "<h2>$month $year</h2>";
	$time = strtotime($month . ' ' . $year);

	// index of first day in the month
	$firstDay = date('w', $time);
	$numDays = date('t', $time);
?>
<div id="nav">
<?php
	
	// get next month info
	$previous = strtotime('-1 month', $time);
	$next = strtotime('+1 month', $time);

	// previous and next query strings have same url
	$prevQuery = $nextQuery = $_SERVER['PHP_SELF'] . '?';

	// get next & previous month and year & add to query strings
	$prevQuery .= 'month=' . date('F', $previous) . '&year=' . date('Y', $previous);
	$nextQuery .= 'month=' . date('F', $next) . '&year=' . date('Y', $next);

	// display html previous and next links
	echo "
	     <a id=\"prev\" href=\"$prevQuery\">Previous Month</a>
	     <a id=\"next\" href=\"$nextQuery\">Next Month</a>
	";
?>
</div>
<table>
	<thead>
		<tr>
		<?php
			
			// loop through header for days of the week
			foreach ($days as $day)
				echo "<th>$day</th>";
		?>
		</tr>
	</thead>
	<tbody>
	<?php

		// start of first row
		echo "<tr>";

		// span first days in month if first day is not sunday
		if ($firstDay != 0)
		   echo "<td colspan=\"$firstDay\"></td>";
		
		// start adding days at first day index
		for ($i = $firstDay; $i < $numDays + $firstDay; $i++) {

		    // get day value
		    $day = $i - $firstDay + 1;
		    echo "<td>$day</td>";

		    // check for end of row
		    if (($i + 1) % 7 == 0) {

		       // end current row
		       echo "</tr>";

		       // start new row
		       echo "<tr>";
		    }
		}
		echo "</tr>";
	?>
	</tbody>
</table>
<?php
	
	// array of colors for selection dropdown
	$colors = array('blue', 'green', 'grey', 'purple', 'yellow', 'orange', 'red', 'white');
?>
<div id="settings">
     <form action="" method="POST">
     	   <div id="leftForm">
	        	   <h5>Select Background Color:</h5>
	   		   <select name="color">
			   	   <?php
			   	   foreach ($colors as $color)
				   	   echo "<option value='$color'>$color</option>";
			   	   ?>
			   </select>
           </div>
	   <div id="rightForm">
	   	<h5>Select Font Size: <span id="fontSize"></span></h5>
	   	<input onchange="updateSize()" type="range" max="35" min="10" name="size">
	   </div>
	   <input type="submit">	   
     </form>
</div>
</body>
</html>