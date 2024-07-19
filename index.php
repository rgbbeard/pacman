<?php
require_once "pacman.apt.class.php";

$pm = new PacMan();
$request = empty($_GET["l"]) ? "I" : $_GET["l"];
$programs_found = $pm->get_installed_packages();
?>

<!doctype html>
<html>
	<head>
		<title>Package Manager</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="/res/css/main.css" rel="stylesheet"/>
		<script type="module" src="/res/js/prototypes.js"></script>
		<script type="module" src="/res/js/html/prototypes.js"></script>
		<script type="module" src="/res/js/main.js"></script>
	</head>
	<body>
		<section>
			<span>Installed programs</span>
			<div class="installed-programs">
				<input type="text" id="search_program" placeholder="Search program.."/>
				<?php
				switch($request) {
					case "I":
						foreach($programs_found as $program => $description) {
							echo "<p class=\"program\" data-name=\"$program\" data-version=\"$description\">
								<b>$program - $description</b>
								<span class=\"program-description\">$description</span>
							</p>";
						}
						break;
					case "A":
						break;
					default:
						echo "<p>Please tell me what to display</p>";
						break;
				}
				?>
			</div>
		</section>
	</body>
</html>