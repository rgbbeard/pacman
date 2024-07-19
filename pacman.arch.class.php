<?php
class PacMan {
	private const pacman = "pacman ";

	# Standard package research
	private const search = self::pacman . "-Ss %s ";

	# Advanced package research
	private const advsearch = self::search . "| grep %s";

	private const install = self::pacman . "-Sy %s ";

	# List all the installed packages
	private const allinstalled = self::pacman . "-Qs ";

	# List package from package file
	private const rvfilesearch = self::pacman . "-Qo %s";

	/**
	 * Remove package
	 * 
	 * this could fail if the package is needed
	 * by other packages
	 */
	private const safeuninstall = self::pacman . "-Rsu %s";

	# Force remove package even if needed by other packages
	private const forceuninstall = self::pacman . "-Rc %s";

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
		$infos = explode("\n", $infos);
		
		for($x = 0;$x<count($infos);$x++) {
			$i = $infos[$x];

			# Package description
			if(preg_match("/^\s+/", $i)) {
				$result[$infos[$x-1]] = trim($i);
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

	public static function safeUninstall(string $name, ?string $version = null): bool {
		if(!empty($name)) {
			$cmd = sprintf(self::safeuninstall, $name);
			shell_exec($cmd);

			return true;
		}

		return false;
	}

	public static function forceUninstall(string $name, ?string $version = null): bool {
		if(!empty($name)) {
			$cmd = sprintf(self::forceuninstall, $name);
			shell_exec($cmd);

			return true;
		}

		return false;
	}
}
?>