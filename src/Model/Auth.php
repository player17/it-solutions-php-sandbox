<?php

namespace Chat\Model;

use \Chat\Injector;

/**
 * Model working with user registration.
 */
class Auth extends Model
{

    public function __construct() {

    }

    /**
     * Check user for existence in table users.
     *
     * @param string|int $login  Login user.
     *
     * @return TRUE|FALSE  Result user verification.
     */
    public function checkUserInBD($login, $date = []) {
        $dbh = Injector::make('MySQL');
        $sql = 'SELECT * from `users` WHERE `login` = "'.$login.'"';

        $stmt = $dbh->prepare($sql);
        $stmt->execute($date);
        $response = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($response) {
            return 'Логин занят';
        } else {
            echo 'Зарегистрировать пользователя и создать ему страницу';
            return 'Пользователя нужно регистрировать';
        }
    }

}
