<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Plantetpedia - Search</title>

	<link href="style.css" rel="stylesheet">
</head>
<body>
	<div class="nav-bar">
		<ul>
			<li class="planetpedia"><a href="index.php">Planetpedia</a></li>
			<div class="rightAlign">
				<li><a href="index.php">Home</a></li>
				<li><a href="browse.php">Browse</a></li>
				<li><a class="active" href="search.php">Search</a></li>
			</div>
		</ul>
	</div>

	<div class="body-content">
		<?php
			if (!isset($_GET['query']) || !$_GET['query']) {
				$query = "";
			}
			else {
				$query = $_GET['query'];
			}
			//check for sort type, if a sort type has been given
			if (!isset($_GET['sortType']) || !$_GET['sortType']) {
				$sortType = 0;
			}
			else {
				$sortType = $_GET['sortType'];
			}
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
		?>

		<h1>Search</h1>
		<div class="container">
			<form id="search-form" action="search.php">
				<div class="inlineLeft">
					<input type="search" name="query" placeholder="Search for..." <?php echo "value=\"$query\"" ?>>
					<input type="submit" value="Search">
				</div>
				<div class="inlineRight">
					<select name="sortType" onchange="<?php if ($query) {echo "this.form.submit();";}?>">
					<option disabled hidden <?php if($sortType == 0) {echo "selected";} ?> value="0">Sort by...</option>
					<option value="1" <?php if($sortType == 1) {echo "selected";} ?>>Alphabetically</option>
					<option value="2" <?php if($sortType == 2) {echo "selected";} ?>>Distance from the Sun</option>
					<option value="3" <?php if($sortType == 3) {echo "selected";} ?>>Size Relative to Earth</option>
					</select>
				</div>
			</form>
		<div>

		<br><br><br>
		<?php
			if (!$query) {
				exit();
			}
			echo "<h2 id='search-query'>Showing results for \"$query\"</h2>";

			if (file_exists("planets.xml")) {
			 	$planets = simplexml_load_file("planets.xml");
				//array to store all stellar bodies that we are to interate over
				$bodies = array();
				//loop to search all planets for our search term
			 	foreach ($planets->children() as $planet) {
			 		// if query matches planet name
			 		if (strpos(strtolower($planet->title), strtolower($query)) !== false) {
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

			 		// Search through moons too
			 		if (isset($planet->moons)) {
			 			foreach ($planet->moons->children() as $moon) {
			 				// if query matches moon name
			 				if (strpos(strtolower($moon->title), strtolower($query)) !== false) {
								//and add them to bodies array
								$bodies[] = array(
									'title' => (string)$moon->title,
									'type' => (string)$moon->type,
									'size' => (double)$moon->size,
									'sunDistance' => (double)$moon->sunDistance,
									'image' =>  (string)$moon->image,
									//extra moonOf value, to specify is a moon
									'moonOf' => $planet->title,
								);
			 				}
			 			}
			 		}
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
				//after sorting, loop through each item in $bodies, to print divs as results
				foreach ($bodies as $body){
					//different code needed for if an item is a moon
					if (isset($body['moonOf'])){
						echo "<div class='planet' onclick='location.href=\"moon.php?planet=".$body['moonOf']."&title=".$body['title']."\";' style='cursor: pointer;'>";
						echo "<div class='info'>";
						echo "<h3>".$body['title']." (Moon)</h3>";
					}
					//otherwise, its a planet
					else {
						echo "<div class='planet' onclick='location.href=\"planet.php?title=".$body['title']."\";' style='cursor: pointer;'>";
						echo "<div class='info'>";
						echo "<h3>".$body['title']."</h3>";
					}
					//remainder of div printing here
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
		?>
	</div>
</body>
</html>
