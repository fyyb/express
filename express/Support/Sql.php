<?php

declare(strict_types=1);

namespace Fyyb\Support;

use \PDO;
use \PDOException;

/**
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class Sql extends PDO
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function __construct(array $config)
    {
        try {
            $this->pdo = new PDO(
                $config['driver'] . ':host=' . $config['host'] . ';dbname=' . $config['database'],
                $config['user'],
                $config['password'],
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::FETCH_ASSOC => true,
                )
            );

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die(print 'Error accessing the database =(');
        };
        return;
    }

    private function setParams($sql, $parameters = array())
    {
        foreach ($parameters as $key => $value) {
            $this->setParam($sql, $key, $value);
        };
    }

    private function setParam($sql, $key, $value)
    {
        $sql->bindValue($key, $value);
    }

    public function query($rowQuery, $params = array())
    {
        $sql = $this->pdo->prepare($rowQuery);
        $this->setParams($sql, $params);
        $sql->execute();
        return $sql;
    }

    public function select($rowQuery, $params = array())
    {
        $sql = $this->query($rowQuery, $params);
        return $sql->fetch();
    }

    public function selectAll($rowQuery, $params = array())
    {
        $sql = $this->query($rowQuery, $params);
        return $sql->fetchAll();
    }

    public function lastId()
    {
        return $this->pdo->lastInsertId();
    }
}