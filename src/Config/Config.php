<?php

namespace Config;

class Config
{
	protected $config_path;
    protected $config = array();
	protected $default_config_file = "<?php\n\nreturn [\t\n\t'database' => [\n\t\t'adapter'\t\t\t=> 'Mysql',\n\t\t'host'\t\t\t\t=> 'localhost',\n\t\t'username'\t\t\t=> 'root',\n\t\t'password'\t\t\t=> '',\n\t\t'dbname'\t\t\t=> 'test',\n\t\t'charset'\t\t\t=> 'utf8',\n\t],\n\t'application' => [\n\t\t'name'\t\t\t\t=> 'base-app',\n\t\t'appDir'\t\t\t=> APP_PATH . '/',\n\t\t'componentsDir'\t\t=> APP_PATH . '/Components/',\n\t\t'configDir'\t\t\t=> APP_PATH . '/Config/',\n\t\t'controllersDir'\t=> APP_PATH . '/Controllers/',\n\t\t'modelsDir'\t\t\t=> APP_PATH . '/Models/',\n\t\t'migrationsDir'\t\t=> APP_PATH . '/Migrations/',\n\t\t'viewsDir'\t\t\t=> APP_PATH . '/Views/',\n\t\t'pluginsDir'\t\t=> APP_PATH . '/Plugins/',\n\t\t'libraryDir'\t\t=> APP_PATH . '/Library/',\n\t\t'formsDir'\t\t\t=> APP_PATH . '/Forms/',\n\t\t'cacheDir'\t\t\t=> BASE_PATH .'/Cache/',\n\t\t'baseUri'\t\t\t=> '/',\t\n\t],\n];";

	public function __construct($config_path) {
		$this->config_path = $config_path;
		if (is_file($this->config_path)) {
			$this->config = include_once ($this->config_path);
		}else{
			if (file_put_contents($this->config_path, $this->default_config_file))
				$this->config = include_once ($this->config_path);
			return $this->config;
		}
		return;
	}

    public function get()
    {
        return json_decode(json_encode($this->config), false);
    }

	public function set($key, $value) {
		$this->config->$key = $value;
		$config_encoded = "<?php\n\nreturn [\n" . $this->printarray($this->config, "\t") . "];\n";
		return file_put_contents($this->config_path, $config_encoded);
	}

	private function printarray($arr, $addtab) {
		$tab = $addtab;
		$string = '';
		foreach($arr as $key => $val) {
			$type = ucfirst(gettype($val));
			if($type	== "String"	) { $string .= $tab."'" . $key . "' => '" . $val .  "'" . ",\n"; }
			elseif($type	== "Integer") { $string .= $tab . "'".$key."'" . " => " . $val . ",\n"; }
			elseif($type	== "Double"	) { $string .= $tab . "'".$key."'" . " => " . $val . ",\n"; }
			elseif($type	== "Boolean") { ($val == 1) ? $val_bool = "true": $val_bool = "false"; $string .= $tab . "'".$key."'" . " => " . $val_bool . ",\n"; }
			elseif($type	== "NULL"	) { $string .= $tab . "'".$key."'" . " => " . "null" . ",\n"; }
			if ($type == 'Array') {
				$string .= $tab."'" . $key . "' => [\n";
				$string .= $this->printarray($val, "\t".$tab);
				$string .= $tab."],\n";
			}
		}
		return $string;
	}
}