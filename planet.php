<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Plantetpedia</title>

	<link href="style.css" rel="stylesheet">
</head>
<body>
	<div class="nav-bar">
		<ul>
			<li class="planetpedia"><a href="index.php">Planetpedia</a></li>
			<div class="rightAlign">
				<li><a href="index.php">Home</a></li>
				<li><a href="browse.php">Browse</a></li>
				<li><a href="search.php">Search</a></li>
			</div>
		</ul>
	</div>
	
	<div class="body-content">
		<div class="planet-detailed">
			<div class="planet-detailed-info">
				<?php
					if (!isset($_GET['title']) || !$_GET['title']) {
						echo "<h1>No planet specified</h1>";
					}
					else {
						$title = $_GET['title'];

						if (file_exists("planets.xml")) {
					 		$planets = simplexml_load_file("planets.xml");

					 		$queriedPlanets = $planets->xpath("//planet[title='$title']");
					 		
					 		if (count($queriedPlanets) == 0) {
								echo "<h1>Can't find planet \"$title\"</h1>";
					 		}
					 		else {
					 			$queriedPlanet = $queriedPlanets[0];
					 			echo "<h1>$queriedPlanet->title</h1>";
					 			echo "<h2>About</h2>";
					 			echo "<p>$queriedPlanet->about</p>";
					 			echo "<h2>Type</h2>";
					 			echo "<p>$queriedPlanet->type</p>";
					 			echo "<h2>Size</h2>";
					 			echo "<p>$queriedPlanet->size Earths</p>";
					 			echo "<h2>Orbital Period</h2>";
					 			echo "<p>$queriedPlanet->orbitPeriod days</p>";
					 			echo "<h2>Discovery Date</h2>";
					 			echo "<p>$queriedPlanet->discovered</p>";
					 			echo "<h2>Sun Distance</h2>";
					 			echo "<p>$queriedPlanet->sunDistance AUs (Astronomical Units)</p>";

					 			if (property_exists($queriedPlanet, "moons")) {
									echo "<h2>Moons</h2>";

									echo "<ul>";
									foreach ($queriedPlanet->moons->children() as $moon) {
										echo "<li><a href=\"moon.php?planet=$queriedPlanet->title&title=$moon->title\">$moon->title</a></li>";
									}
									echo "</ul>";
					 			}
					 		}
					 	}
					}
				?>
			</div>
			<div class="planet-detailed-picture">
				<?php
					if (isset($queriedPlanet)) {
						echo "<img src='images/$queriedPlanet->image'>";
						echo "<p><i>A picture of $queriedPlanet->title</i></p>";
						echo "<p>Source: Wikipedia</p>";
					}
				?>
			</div>
		</div>
	</div>
</body>
</html>
