<?php

namespace Chat\Model;

use \Chat\Injector;

/**
 * Model working with user registration.
 */
class Auth extends Model
{
    /**
     * PDO connect
     *
     * @var PDO
     */
    public $db = null;

    public function __construct() {
        $this->db = Injector::make('MySQL');
    }

    /**
     * Check user for existence in table users.
     *
     * @param string $login  Login user.
     * @param string $pass  Password user.
     *
     * @return boolean|int Result user verification.
     */
    public function checkUserInBD($login, $pass) {
        // TODO Rafikov Сделать через трайд
        $dbh = $this->db;
        $sql = 'SELECT `id` FROM `users` WHERE `login` = :login';

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        $response = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($response) {
            return FALSE;
        } else {
            $sql = 'INSERT INTO `users` (`login`,`pass`) VALUES(:login, :pass);';
            //echo $sql; die();
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':pass', $pass);
            $stmt->execute();

            /*
            $sql = 'SELECT `AUTO_INCREMENT`
                    FROM  INFORMATION_SCHEMA.TABLES
                    WHERE TABLE_SCHEMA = \'chat\'
                    AND   TABLE_NAME   = \'users\'';
            */
            $sql = 'SELECT `id`
                    FROM  `users`
                    WHERE `login` = :login';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':login', $login);
            $stmt->execute();
            $idUser = $stmt->fetch(\PDO::FETCH_ASSOC)['id'];

            return $idUser;
        }
    }

    /**
     * Check password user in datebase.
     *
     * @param string $login  Login user.
     * @param string $pass  password user.
     *
     * @return boolean|int Result check password.
     */
    public function checkPassUser($login, $pass) {

        $dbh = $this->db;
        $sql = 'SELECT `id`,`pass` FROM `users` WHERE `login` = :login';

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        $passHash = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(password_verify($pass, $passHash['pass'])) {
            return $passHash['id'];
        } else {
            return FALSE;
        }

    }

}
