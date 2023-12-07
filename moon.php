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
					if (!isset($_GET['planet']) || !$_GET['planet']) {
						echo "<h1>No planet specified</h1>";
					}
					else if (!isset($_GET['title']) || !$_GET['title']) {
						echo "<h1>No moon specified</h1>";
					}
					else {
						$title = $_GET['title'];
						$planet = $_GET['planet'];

						if (file_exists("planets.xml")) {
					 		$planets = simplexml_load_file("planets.xml");

					 		$queriedPlanets = $planets->xpath("//planet[title='$planet']");
					 		
					 		if (count($queriedPlanets) == 0) {
								echo "<h1>Can't find planet \"$planet\"</h1>";
					 		}
					 		else {
					 			$queriedPlanet = $queriedPlanets[0];
					 			$queriedPlanetMoons = $queriedPlanet->moons->xpath("//moon[title='$title']");
					 		
						 		if (count($queriedPlanetMoons) == 0) {
									echo "<h1>Can't find moon \"$title\"</h1>";
						 		}
						 		else {
					 				$queriedMoon = $queriedPlanetMoons[0];
					 				echo "<small><a href=\"planet.php?title=$queriedPlanet->title\">&laquo; Back to $queriedPlanet->title</a></small>";

						 			echo "<h1>$queriedMoon->title</h1>";
						 			echo "<h2>Orbits Around</h2>";
						 			echo "<p>$queriedPlanet->title</p>";
						 			echo "<h2>About</h2>";
						 			echo "<p>$queriedMoon->about</p>";
						 			echo "<h2>Type</h2>";
						 			echo "<p>$queriedMoon->type</p>";
						 			echo "<h2>Size</h2>";
						 			echo "<p>$queriedMoon->size Earths</p>";
						 			echo "<h2>Orbital Period</h2>";
						 			echo "<p>$queriedMoon->orbitPeriod days</p>";
						 			echo "<h2>Discovery Date</h2>";
						 			echo "<p>$queriedMoon->discovered</p>";
						 			echo "<h2>Sun Distance</h2>";
						 			echo "<p>$queriedMoon->sunDistance AUs (Astronomical Units)</p>";
						 		}
					 		}
					 	}
					}
				?>
			</div>
			<div class="planet-detailed-picture">
				<?php
					echo "<img src='images/$queriedMoon->image'>";
					echo "<p><i>A picture of $queriedMoon->title</i></p>";
					echo "<p>Source: Wikipedia</p>";
				?>
			</div>
		</div>
	</div>
</body>
</html>
