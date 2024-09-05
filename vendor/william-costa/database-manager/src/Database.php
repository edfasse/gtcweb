<?php

namespace WilliamCosta\DatabaseManager;

use \PDO;
use \PDOException;

class Database{

  /**
   * Host de conexão com o banco de dados
   * @var string
   */
  private static $host;

  /**
   * Nome do banco de dados
   * @var string
   */
  private static $name;

  /**
   * Usuário do banco
   * @var string
   */
  private static $user;

  /**
   * Senha de acesso ao banco de dados
   * @var string
   */
  private static $pass;

  /**
   * Porta de acesso ao banco
   * @var integer
   */
  private static $port;

  /**
   * Nome da tabela a ser manipulada
   * @var string
   */
  private $table;

  /**
   * Instancia de conexão com o banco de dados
   * @var PDO
   */
  private $connection;

  private $finaly;

  /**
   * Método responsável por configurar a classe
   * @param  string  $host
   * @param  string  $name
   * @param  string  $user
   * @param  string  $pass
   * @param  integer $port
   */
  public static function config($host,$name,$user,$pass,$port = 3306){
    self::$host = $host;
    self::$name = $name;
    self::$user = $user;
    self::$pass = $pass;
    self::$port = $port;
  }

  /**
   * Define a tabela e instancia e conexão
   * @param string $table
   */
  public function __construct($table = null){
    $this->table = $table;
    $this->setConnection();
  }

  /**
   * Método responsável por criar uma conexão com o banco de dados
   */
  private function setConnection(){
    try{
      $this->connection = new PDO('mysql:host='.self::$host.';dbname='.self::$name.';port='.self::$port,self::$user,self::$pass);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
      die('ERROR: '.$e->getMessage());
    }
  }

  /**
   * Método responsável por executar queries dentro do banco de dados
   * @param  string $query
   * @param  array  $params
   * @return PDOStatement
   */
  public function execute($query,$params = []){
    try{
      $statement = $this->connection->prepare($query);
      $statement->execute($params);
      return $statement;
    }catch(PDOException $e){
      die('ERROR: '.$e->getMessage());
    }
  }

  /**
   * Método responsável por inserir dados no banco
   * @param  array $values [ field => value ]
   * @return integer ID inserido
   */
  public function insert($values){
    //DADOS DA QUERY
    $fields = array_keys($values);
    $binds  = array_pad([],count($fields),'?');

    //MONTA A QUERY
    $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';

    //EXECUTA O INSERT
    $this->execute($query,array_values($values));

    //RETORNA O ID INSERIDO
    return $this->connection->lastInsertId();
  }

  /**
   * Método responsável por executar uma consulta no banco
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $fields
   * @return PDOStatement
   */
  public function select($where = null, $order = null, $limit = null, $fields = '*', $inner = ''){
        //DADOS DA QUERY
        if (!$where == null) {
          $where = strlen($where) ? 'WHERE '.$where : '';
        }
        if (!$order == null) {
          $order = strlen($order) ? 'ORDER BY '.$order : '';
        }
        if (!$limit == null) {
          $limit = strlen($limit) ? 'LIMIT '.$limit : '';
        }
        //MONTA A QUERY
        if(strlen($inner)) {
          $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$inner.' '.$where.' '.$order.' '.$limit;
        } else {
          $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;
        }
        //EXECUTA A QUERY
        return $this->execute($query);
  }
  /**
   * Método responsável por executar uma consulta no banco
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $fields
   * @return PDOStatement
   */
  public function get($where = null, $order = null, $limit = null, $fields = '*', $inner = ''){
        //DADOS DA QUERY
        if (!$where == null) {
          $where = strlen($where) ? 'WHERE '.$where : '';
        }
        if (!$order == null) {
          $order = strlen($order) ? 'ORDER BY '.$order : '';
        }
        if (!$limit == null) {
          $limit = strlen($limit) ? 'LIMIT '.$limit : '';
        }
        //MONTA A QUERY
        if(strlen($inner)) {
          $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$inner.' '.$order.' '.$limit;
        } else {
          $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;
        }

        //EXECUTA A QUERY
        return $this->execute($query);
  }
  
		/**
		 * METODO RESPONSAVEL POR SELECIONAR PARTE DOS DADOS DE SELECAO
		 * @param string ...$parmas
		 * @return $this
		 */
		public function where( string ...$params ) {
			$field    = $params[0];
			$operator = (count($params) > 2) ? $params[1] : '=';
			$value    = isset($params[2]) ? $params[2] : $params[1];
			$value    = is_numeric($value) ? $value : "'".$value."'";
			$where    = preg_match('/WHERE/', $this->finaly ?? '') ? 'OR' : 'WHERE'; 
			// BUILD QUERY
			$query = $this->finaly ?? 'SELECT * FROM '.$this->table;
			$query = $query.' '.$where.' '.$field.' '.$operator.' '.$value;

			$this->finaly = $query;
			
			return $this;
		}

  /**
   * Método responsável por executar atualizações no banco de dados
   * @param  string $where
   * @param  array $values [ field => value ]
   * @return boolean
   */
  public function update($where,$values){
    //DADOS DA QUERY
    $fields = array_keys($values);

    //MONTA A QUERY
    $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;

    //EXECUTAR A QUERY
    $this->execute($query,array_values($values));

    //RETORNA SUCESSO
    return true;
  }

  
  /**
   * Método responsável por executar atualizações no banco de dados
   * @param  string $where
   * @param  array $values [ field => value ]
   * @return boolean
   */
  public function update_without_where($values){
    //DADOS DA QUERY
    $fields = array_keys($values);

    //MONTA A QUERY
    $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? ';

    //EXECUTAR A QUERY
    $this->execute($query,array_values($values));

    //RETORNA SUCESSO
    return true;
  }

		/**
		 * METODO RESPONSAVEL POR RETORNAR O PRIMEIRO RESULTADO DA CONSULTA
		 */
		public function first() {
			$database_result = $this->execute($this->finaly);
			return $database_result->fetchObject(self::class) ?? null;
		}

  /**
   * Método responsável por excluir dados do banco
   * @param  string $where
   * @return boolean
   */
  public function delete($where){
    //MONTA A QUERY
    $query = 'DELETE FROM '.$this->table.' WHERE '.$where;

    //EXECUTA A QUERY
    $this->execute($query);

    //RETORNA SUCESSO
    return true;
  }

  public function getDate(){

    $query = "SELECT CURRENT_DATE as date";
    //EXECUTA A QUERY
    return $this->execute($query);
    
  }

}