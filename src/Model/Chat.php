<?php

namespace Chat\Model;

use \Chat\Injector;

/**
 * Model working with user registration.
 */
class Chat extends Model
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
     * Check password user in datebase.
     *
     * @param string $login  Login user.
     * @param string $pass  password user.
     *
     * @return boolean|int Result check password.
     */
    public function checkLogin($login) {

        $dbh = $this->db;
        $sql = 'SELECT `id` FROM `users` WHERE `login` = :login';

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        $checkUser = $stmt->fetch(\PDO::FETCH_ASSOC)['id'];

        if($checkUser) {
            return $checkUser;
        } else {
            return FALSE;
        }

    }

    /**
     * Chat feed history
     *
     * @param int $to  id user.
     * @param int $from  id from user.
     *
     * @return array feed history.
     */
    public function historyChat($to, $from) {

        $dbh = $this->db;
        // TODO Rafikov определиться с оптимальным запросом к чату
        /*
        $sql = '
          (SELECT `id`,`to`,`do`,`comment`,`date` 
          FROM `chats` 
          WHERE 
            `to` = :to 
            AND `from` = :from)
          UNION
          (SELECT `id`,`to`,`do`,`comment`,`date` 
          FROM `chats` 
          WHERE 
            `to` = :from 
            AND `from` = :to)
        ';
        */
        $sql = '
          SELECT 
            `id`,
            `to`,
            `from`,
            `comment`,
            `date` 
          FROM 
            `chats` 
          WHERE 
            (`to` = :to AND `from` = :from)
            OR
            (`to` = :from AND `from` = :to)
          ORDER BY 
            `id` ASC
        ';

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':to', $to);
        $stmt->bindParam(':from', $from);
        $stmt->execute();

        $feedChat = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if($row['to'] == $to) {
                $row['direction'] = 'left';
            } else {
                $row['direction'] = 'right';
            }
            $feedChat[] = $row;
        }

        return $feedChat;

    }

    /**
     * Add msg in table Chats
     *
     * @param int $to  id to user.
     * @param int $from  id from user.
     * @param string $msg  text msg.
     *
     * @return boolean result add msg in db.
     */
    public function setMsg($to, $from, $msg) {
        $dbh = $this->db;
        $sql = '
          INSERT INTO `chats`
            (`to`,`from`,`comment`,`date`)
          VALUES(:to, :from, :msg, :date);
        ';

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':to', $to);
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':msg', $msg);
        $stmt->bindParam(':date', date('Y-m-d H:i:s'));
        $stmt->execute();

        if (!$stmt) {
            echo "\nPDO::errorInfo():\n";
            print_r($dbh->errorInfo());
        } else {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
    }

}
