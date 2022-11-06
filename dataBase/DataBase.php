<?php

/**
 * Author: Mikita Filonau
 * Create date: 06.11.09.55
 * Class for work with dataBase
 * TODO:
 * [+] Separation of connection and work with the db.
 * [+] Create file "config".
 * Class DataBase wrapper for working with PDO.
 * Creates a connection to a mysql database using a password,
 * username, encoding, and host.
 * The class contains database queries
 * that return a Person object or an array of People.
 */
class DataBase
{

    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASSWORD = 'password';
    const DB_NAME = 'TestStyleWomenMax1';
    const CHARSET = 'utf8';

    static private $db;

    protected static $instance = null;


    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (self::$instance === null) {
            try {
                self::$db = new PDO(
                    'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME,
                    self::DB_USER,
                    self::DB_PASSWORD,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . self::CHARSET
                    ]
                );
            } catch (PDOException $e) {
                throw new Exception ($e->getMessage());
            }
        }
        return self::$instance;
    }

    public static function query($stmt): PDOStatement
    {
        return self::$db->query($stmt);
    }

    public static function prepare($stmt): PDOStatement
    {
        return self::$db->prepare($stmt);
    }


    /**
     * @throws Exception
     */
    public static function run($query, $args = []): PDOStatement
    {
        try {
            if (!$args) {
                return self::query($query);
            }
            $stmt = self::prepare($query);
            $stmt->execute($args);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public static function getRow($query, $args = [])
    {
        return self::run($query, $args)->fetch();
    }

    /**
     * @throws Exception
     */
    public static function getColumn($query, $args = []): array
    {
        return self::run($query, $args)->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * @throws Exception
     */
    public static function sql($query, $args = []): void
    {
        self::run($query, $args);
    }

    /**
     * @throws Exception
     */
    public static function addPeople($args = []): void
    {
        self::run("INSERT INTO `Person` (`id`, `name`, `secondName`, `birthday`, `gender`, `birthOfPlace`)
            VALUES (:id, :name, :secondName, :birthday, :gender, :birthOfPlace)", $args);
    }

    /**
     * @throws Exception
     */
    public static function getAllId(): array
    {
        return DataBase::getColumn("SELECT `id` FROM `Person`");
    }


}