<?php
ini_set('display_errors', 'On');

class Database
{

    private $config = [];

    public static function connect() {

    }

    public static function getAll() {
        $db = new PDO('mysql:host=localhost;dbname=Palma_Toledo_Luis_DWES04_TareaEvaluativa01;charset=utf8', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $users = $db->query("SELECT * FROM users");
        $users = $users->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }

    public static function getById() {
        $db = new PDO('mysql:host=localhost;dbname=Palma_Toledo_Luis_DWES04_TareaEvaluativa01;charset=utf8', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $users = $db->query("SELECT * FROM users where dni='04622363F'");
        $users = $users->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }


    public static function loadConfig()
    {
        $json_file = file_get_contents('../config/db-conf.json');
        $config = json_decode($json_file, true);

        $db_host = $config['host'];
        $db_user = $config['user'];
        $db_pass = $config['password'];
        $db_bd = $config['db_name'];

        // echo "Host " . $db_host . '<br>';
        // echo "User " . $db_user . '<br>';
        // echo "Pass " . $db_pass . '<br>';
        // echo "BD " . $db_bd . '<br>';
    }
}
