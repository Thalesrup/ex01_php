<?php

/**
     * Classe Core
     */


class CoreDB {
    private $dbName      = 'ex01';
    private $dbUser      = 'root';
    private $dbPassword  = '';
    private $dbHost      = 'localhost';
    private $conexao;

  public function __construct()
    {
        $dsn	= 	"mysql:dbname=".$this->dbName.";host=".$this->dbHost."";
        $pdo	=	  "";
        try {
            $pdo = new \PDO($dsn, $this->dbUser, $this->dbPassword);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return null;
        }

        $this->conexao = $pdo;
        $this->conexao->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        return $this;

    }

    public function getPDO()
    {
      return $this->conexao;
    }
}