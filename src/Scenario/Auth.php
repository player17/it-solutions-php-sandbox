<?php

namespace Chat\Scenario;

use \Chat\Http\Request;
use \Chat\Http\Params;
use \Chat\Inject;
use \Chat\Injector;
use \Chat\Scenario;
use \Chat\Model;

/**
 * Implements scenarios of authentication page for unauthorized visitors.
 */
class Auth implements Scenario
{
    use Inject\HtmlRenderer;

    public $authModel = null;

    public function __construct()
    {
        $this->authModel = new Model\Auth();
    }


    /**
     * Runs scenario of index page.
     *
     * @param Request $req      HTTP request to index page.
     *
     * @return array    Result of index page scenario.
     */
    public function run(Request $req): array
    {
        $visibleForm = true;
        $searchLogin = false;
        $enterForm = true;
        $title = 'Auth page';
        $arrayRegUsers = null;

        $post = $req->POST->getAll();
        if($post != [] && is_array($post)) {

            if($req->POST->String('typeForm') =='auth') {
                $visibleForm = false;

                $login = $req->POST->String('login');
                $pass = $req->POST->passwordHash('pass');

                $modelAuth = $this->authModel;
                if($idUser = $modelAuth->checkUserInBD($login, $pass)) {
                    $title = 'Здравствуй, ' . $login;

                    // TODO Rafikov создать класс по работе с куками
                    setcookie("AUTH", 'TRUE', time()+3600);
                    setcookie("idUser", $idUser, time()+3600);
                    setcookie("login", $login, time()+3600);
                    $searchLogin = true;
                    $enterForm = false;
                } else {
                    $title = 'Есть такой пользователь';
                    $visibleForm = true;
                }
            } else if($req->POST->String('typeForm') =='enter') {
                // TODO Rafikov проверка пользователя в базе и хеш пароля
                $login = $req->POST->String('login');
                $pass = $req->POST->String('pass');

                $modelAuth = $this->authModel;
                if($idUser = $modelAuth->checkPassUser($login, $pass)) {
                    $visibleForm = false;
                    $enterForm = false;
                    $title = 'Здравствуй, ' . $login;

                    setcookie("AUTH", 'TRUE', time()+3600);
                    setcookie("idUser", $idUser, time()+3600);
                    setcookie("login", $login, time()+3600);
                    $searchLogin = true;

                    $arrayRegUsers = $this->arrayAllRegUser($idUser);

                } else {
                    $title = 'Неверный логин или пароль';
                }

            }


        }
        return ['toRender' => [
            'title' => $title,
            'form' => $visibleForm,
            'searchLogin' => $searchLogin,
            'enterForm'=> $enterForm,
            'arrayRegUsers' => $arrayRegUsers,
        ]];
    }

    /**
     * Returns for all users in the database.
     *
     * @param $idUser id user.
     *
     * @return array list all reg users.
     */
    public function arrayAllRegUser($idUser) : array
    {

        return $this->authModel->allRegUsers($idUser);

    }

}

