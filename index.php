<?php
require_once "utils.php";
require_once "pacman.class.php";
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
		<link rel="icon" href="/res/img/icon.png">
		<link href="/res/css/lib/global.css" rel="stylesheet"/>
		<link href="/res/css/main.css" rel="stylesheet"/>
		<script type="module" src="/res/js/lib/prototypes.js"></script>
		<script type="module" src="/res/js/lib/html/prototypes.js"></script>
		<script type="module" src="/res/js/main.js"></script>
	</head>
	<body>
		<section class="filters">
			<button class="btn old-school" id="filter-remove">
				Show all
			</button>
			<button class="btn old-school" id="filter-only-upgradable">
				Only upgradable
			</button>
		</section>
		<section>
			<span>Installed programs</span>
			<div class="installed-programs">
				<input type="text" id="search_program" placeholder="Search program..">
				<?php
				switch($request) {
					case "I":
						foreach($programs_found as $program => $data) {
							$version = $data[0];
							$description = htmlspecialchars($data[1]);
							$upgradable = $data[2] ? "(upgradable)" : "";
							echo "<p class=\"program\" data-name=\"$program\" data-version=\"$version\">
								<b>$program $upgradable - $version</b>
								<span class=\"program-description\">$description</span>
							</p>";
						}
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