<?php

	// get calendar month from url string or set to current month
	$month = isset($_GET['month']) ? $_GET['month'] : date('M');

	// get year from query string or set to current year
	$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
?>
<!DOCTYPE html>
<html>
<head>
	<title>PHP Calendar</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
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
</body>
</html>