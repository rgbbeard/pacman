<?php
if($_SERVER["REQUEST_METHOD"] === "POST") {
	if(!@empty($_POST["name"])) {
		require_once "../pacman.class.php";

		$pm = new PacMan();

		if($pm->package_is_installed($_POST["name"])) {
			$fix_broken = isset($_POST["fix_broken"]) && intval($_POST["fix_broken"]) == 77;

			$pm->update_package($_POST["name"], $fix_broken) ? die("ok") : die("ko");
		}
	}
}

die("ko");