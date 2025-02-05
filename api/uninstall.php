<?php
if($_SERVER["REQUEST_METHOD"] === "POST") {
	if(!@empty($_POST["name"])) {
		require_once "../pacman.class.php";

		$pm = new PacMan();

		if($pm->package_is_installed($_POST["name"])) {
			if(isset($_POST["force"]) && boolval($_POST["force"]) === true) {
				$pm->force_uninstall($_POST["name"]) ? die("ok") : die("ko");
			}

			$pm->safe_uninstall($_POST["name"]) ? die("ok") : die("ko");
		}
	}
}

die("ko");