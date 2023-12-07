<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Plantetpedia - Browse</title>

	<link href="style.css" rel="stylesheet">
</head>
<body>
	<div class="nav-bar">
		<ul>
			<li class="planetpedia"><a href="index.php">Planetpedia</a></li>
			<div class="rightAlign">
				<li><a href="index.php">Home</a></li>
				<li><a class="active" href="browse.php">Browse</a></li>
				<li><a href="search.php">Search</a></li>
			</div>
			
		</ul>
	</div>
	
	<div class="body-content">
		<?php
		//Function that takes a multidimensional array and sorts it according to one of the 'columns' of data
		//used for sorting items
		function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
				//temp array
				$reference_array = array();

				//loop through each item in passed array, as a key value pair
				foreach($array as $key => $row) {
					//add it into the new array
						$reference_array[$key] = $row[$column];
				}
				//then sort the passed array, using the temp array and the passed 'column'
				array_multisort($reference_array, $direction, $array);
		}
		if (!isset($_GET['sortType']) || !$_GET['sortType']) {
			$sortType = 0;
		}
		else {
			$sortType = $_GET['sortType'];
		}
		?>
		<h1>Browse</h1>

		<div class="container">
			<div class="inlineLeft">
				<h2 id="sortby-query">All planets</h2>
			</div>
			<div class="inlineRight">
				<form id="sortby-form" action="browse.php">
					<!-- Select element to use as to sort results -->
					<!-- Will auto submit the form with the original query when a sort order is selected -->
					<select name="sortType" onchange="this.form.submit()">
						<option disabled hidden <?php if($sortType == 0) {echo "selected";} ?> value="0">Sort by...</option>
						<option value="1" <?php if($sortType == 1) {echo "selected";} ?>>Alphabetically</option>
						<option value="2" <?php if($sortType == 2) {echo "selected";} ?>>Distance from the Sun</option>
						<option value="3" <?php if($sortType == 3) {echo "selected";} ?>>Size Relative to Earth</option>
					</select>
				</form>
			</div>
		</div>
		<p>Click on the planets to find out more about them!</p>
		
		

		<?php
			 if (file_exists("planets.xml")) {
			 	$planets = simplexml_load_file("planets.xml");
				//array to store objects to sort planets
				$bodies = array();
			 	foreach ($planets->children() as $planet) {
					//append each to the bodies array
					//creating an array object to represent each planet
					$bodies[] = array(
						//storing various data needed to build divs for search results
						'title' => (string)$planet->title,
						'type' => (string)$planet->type,
						'size' => (double)$planet->size,
						'sunDistance' => (double)$planet->sunDistance,
						'image' =>  (string)$planet->image,
					);
			 	}
				//now calling the sort function, depending on what option we want to sort by
				switch ($sortType) {
					case 1:
						array_sort_by_column($bodies, 'title');
						break;
					case 2:
						array_sort_by_column($bodies, 'sunDistance');
						break;
					case 3:
						array_sort_by_column($bodies, 'size');
						break;
				}
				//after sorting, loop through each item in $bodies, to print divs for each planet
				foreach ($bodies as $body){
					echo "<div class='planet' onclick='location.href=\"planet.php?title=".$body['title']."\";' style='cursor: pointer;'>";
					echo "<div class='info'>";
					echo "<h3>".$body['title']."</h3>";
					echo "<h4>Type</h4>";
					echo "<p>".$body['type']."</p>";
					echo "<h4>Size</h4>";
					echo "<p>".$body['size']." Earths</p>";
					echo "</div>";
					echo "<div class='picture'>";
					echo "<img src='images/".$body['image']."'>";
					echo "</div>";
					echo "</div>\n";
			 	}
		 	}
			 else {
			 	exit('Failed to open planets.xml file.');
			 }
		?>
	</div>
</body>
</html>
