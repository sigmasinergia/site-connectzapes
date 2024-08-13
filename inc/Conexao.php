<?php
// Configurações iniciais para exibir todos os erros e logá-los em um arquivo
/*
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);
*/
//
// Constantes para parâmetros de conexão
define('HOST', '127.0.0.1');
define('DBNAME', 'mywhatsapp-api');
define('CHARSET', 'utf8mb4');
define('USER', 'mywhatsappapi');
define('PASSWORD', 'aG3Jirx#TuUep8KkjCtAA@');
//
class Conexao
{
    private static $pdo; // Atributo estático que guarda a instância do PDO

    private function __construct()
    {
        // Construtor privado impede a criação direta de objetos dessa classe
    }

    private function __clone()
    {
        // Método privado impede a clonagem de instâncias dessa classe
    }

    public function __destruct()
    {
        // Destruidor desconecta e limpa a conexão e variáveis quando o objeto é destruído
        $this->disconnect();
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }

    public static function conectar()
    {
        // Método estático que retorna uma instância do PDO
        if (!isset(self::$pdo)) {
            try {
                // Opções do PDO para iniciar a conexão
                $opcoes = [
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . CHARSET, // Define a codificação
                    PDO::ATTR_PERSISTENT => true, // Conexão persistente
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Erros são tratados como exceções
                    PDO::ATTR_EMULATE_PREPARES => false // Desabilita a emulação de prepares para segurança
                ];
                // DSN - String de conexão com o banco
                $dsn = "mysql:host=" . HOST . "; dbname=" . DBNAME . "; charset=" . CHARSET;
                // Cria a instância do PDO
                self::$pdo = new PDO($dsn, USER, PASSWORD, $opcoes);
            } catch (PDOException $e) {
                // Loga erros de conexão e lança uma exceção
                error_log("Erro de conexão: " . $e->getMessage());
                throw new Exception("Erro de conexão com o banco de dados");
            }
        }
        return self::$pdo;
    }

    private static function disconnect()
    {
        // Método para desconectar efetivamente do banco de dados
        self::$pdo = null;
    }
}
?>