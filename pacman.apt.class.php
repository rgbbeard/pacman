<?php
class PacMan {
	private const pacman = "apt ";

	# Standard package research
	private const search = self::pacman . "search %s ";

	# Advanced package research
	private const advsearch = self::search . "list | grep %s";

	private const install = self::pacman . "install %s";

	# List all the installed packages
	private const allinstalled = self::pacman . "list --installed";

	private const safeuninstall = self::pacman . "remove %s";

	private const forceuninstall = self::pacman . "purge %s";

	public function __construct() {}

	public function package_is_installed(string $name): array {
		$result = [];
		$packages = $this->get_installed_packages();
		
		foreach($packages as $package => $description) {
			if(strpos($package, $name) !== false) {
				$result[$package] = $description;
			}
		}

		return $result;
	}

	public function get_installed_packages(): array {
		$result = [];
		$infos = shell_exec(self::allinstalled);
		$lines = explode("\n", $infos);

		foreach($lines as $line) {
			if (preg_match('/^([^\/]+)\/[^\s]+\s/', $line, $matches)) {
				$package_name = $matches[1];
				$type = preg_match("/^" . $package_name . "\/.*,/", $line, $matches2);

				if(isset($matches2[1])) {
					$type = preg_replace("/^" . $package_name . "\//", "", $matches2[1]);
				}

				$line = preg_replace("/^" . $package_name . "\/.*,/", "", $line);
				$line = preg_replace("/\[installed\]$/", "", $line);

				$result[$package_name] = $type . " - " . $line;
			}
		}

		return $result;
	}

	public function search_package(string $name): array {
		$result = [];

		if(!empty($name)) {
			$cmd = sprintf(self::search, $name);
			$infos = shell_exec($cmd);
			$infos = explode("\n", $infos);

			for($x = 0;$x<count($infos);$x++) {
				$i = $infos[$x];

				# Package description
				if(preg_match("/^\s+/", $i)) {
					$result[$infos[$x-1]] = trim($i);
				}
			}
		}
		
		return $result;
	}

	public static function safe_uninstall(string $name, ?string $version = null): bool {
		if(!empty($name)) {
			$cmd = sprintf(self::safeuninstall, $name);
			shell_exec($cmd);

			return true;
		}

		return false;
	}

	public static function force_uninstall(string $name, ?string $version = null): bool {
		if(!empty($name)) {
			$cmd = sprintf(self::forceuninstall, $name);
			shell_exec($cmd);

			return true;
		}

		return false;
	}
}