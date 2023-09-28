<?php

class Database {
    protected $conn;
    public function __construct() {
        try{
            if($this->loadEnv()){
                if($this->getEnv("DB_CONNECT") == "true"){
                    $db_type = $this->getEnv("DB_TIPO");
                    $db_host = $this->getEnv("DB_HOST");
                    $db_base = $this->getEnv("DB_BASE");
                    $db_cotejamiento = $this->getEnv("COTEJAMIENTO");
                    $db_user = $this->getEnv("DB_USER");
                    $db_pass = $this->getEnv("DB_PASS");
                    $this->conn = new PDO($db_type.$db_host.$db_base.$db_cotejamiento, $db_user, $db_pass);
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                };
             };
        }
        catch(PDOException $e ){
			echo "Fallo la Conexion: ".$e->getMessage();
		 }
	}
    private function loadEnv(){
        try {
            $envFile = __DIR__ . '/../.env';
            if (!file_exists($envFile)) {
                throw new Exception('.env file not found.');
            }
    
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim(str_replace(["'",'"'],"",$value));
    
                    // Define la variable de entorno solo si no está previamente definida
                    if (!array_key_exists($key, $_ENV)) {
                        $_ENV[$key] = $value;
                    }
                }
            }
            return true;
        } catch (\Throwable $e) {
            echo "Error: ".$e->getMessage();
            return false;
        }
    }
    protected function getEnv($key){
        return $_ENV[$key];
    }
}(new Database);

?>
