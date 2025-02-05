<?php
require_once "utils.php";

class PacMan {
	private const separator = "\n@@@@@@@@@@@@@@@\n";
	private const pacman = "apt ";
	private const masterman = "apt-get ";
	private const dpkg = "dpkg ";
	private const query = "dpkg-query ";

	private const allinstalled = self::query . '-W -f=\'${Package}\n\n\n${Version}\n\n\n${Description}' . self::separator . '\'';

	private const upgradable = self::pacman . "list --upgradable";

	private const install = self::pacman . "install %s";

	private const update = self::masterman . "install --only-upgrade %s";

	private const updatefixbroken = self::update . " --fix-missing";

	private const safeuninstall = self::masterman . "remove %s -y";

	private const forceuninstall = self::masterman . "purge %s -y";

	public function package_is_installed(string $name): bool {
		$packages = $this->get_installed_packages();
			
		foreach($packages as $package => $data) {
			$version = $data[0];
			$description = htmlspecialchars($data[1]);
			if(strpos($package, $name) !== false) {
				return true;
			}
		}

		return false;
	}

	public function get_installed_packages(): array {
		$result = [];
		$infos = shell_exec(self::allinstalled);
		$lines = explode(self::separator, $infos);
		$lines = array_trim($lines);

		$upgradable_packages = $this->get_upgradable_packages();

		for($x = 0; $x<count($lines); $x++) {
			$line = explode("\n\n\n", $lines[$x]);
			$package_name = $line[0];
			$version = $line[1];
			$description = $line[2];
			$upgradable = in_array($package_name, $upgradable_packages);

			$result[$package_name] = [
				$version, 
				$description, 
				$upgradable
			];
		}

		return $result;
	}

	public function get_upgradable_packages(): array {
		$result = [];

		$packages = shell_exec(self::upgradable);
		$packages = explode("\n", $packages);
		array_shift($packages);
		$packages = array_trim($packages);

		foreach($packages as $package) {
			$infos = explode("/", $package);
			$name = $infos[0];
			$result[] = $name;
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

	public static function safe_uninstall(string $name): bool {
		if(!empty($name)) {
			$cmd = sprintf(self::safeuninstall, $name);
			die($cmd);
			shell_exec($cmd);

			return true;
		}

		return false;
	}

	public static function force_uninstall(string $name): bool {
		if(!empty($name)) {
			$cmd = sprintf(self::forceuninstall, $name);
			die($cmd);
			shell_exec($cmd);

			return true;
		}

		return false;
	}

	public static function update_package(string $name, bool $fix_broken = false): bool {
		$command = $fix_broken ? self::updatefixbroken : self::update;

		if(!empty($name)) {
			$cmd = sprintf($command, $name);
			shell_exec($cmd);

			return true;
		}

		return false;
	}
}