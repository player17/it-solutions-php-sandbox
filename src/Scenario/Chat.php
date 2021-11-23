<?php

namespace Chat\Scenario;

use \Chat\Http\Request;
use \Chat\Http\Params;
use \Chat\Inject;
use \Chat\Injector;
use \Chat\Scenario;
use \Chat\Model;

class Chat
{
    use Inject\HtmlRenderer;

    public $chatModel = null;
    public $authModel = null;

    public function __construct()
    {
        $this->chatModel = new Model\Chat();
        $this->authModel = new Model\Auth();
    }

    public function run(Request $req): array
    {
        $title = 'Чат';
        $buttonBack = FALSE;
        $formSendMsg = FALSE;
        $post = $req->GET->getAll();
        $arrayFeed = [];
        $arrayUsers = [];
        if($post != [] && is_array($post)) {
            if($req->GET->String('typeForm') == 'search') {
                $login = $req->GET->String('login');

                if($idUser = $this->chatModel->checkLogin($login)) {

                    $title = 'Чат с пользователем ' . $login;

                    $arrayFeed = $this->chatModel->historyChat($idUser,$_COOKIE['idUser']);

                    $formSendMsg = TRUE;
                    $buttonBack = TRUE;

                    $arrayUsers = $this->listUsers($_COOKIE['idUser']);

                } else {

                    $title = 'Нет такого пользователя';
                    $buttonBack = TRUE;

                }
            }
            if($req->GET->String('typeForm') == 'msg') {

                $login = $req->GET->String('loginUser');
                $idUser = $this->chatModel->checkLogin($login);
                $title = 'Чат с пользователем ' . $login;

                $this->chatModel->setMsg(
                    $req->GET->String('to'),
                    $req->GET->String('from'),
                    $req->GET->String('textMsg')
                );

                $arrayFeed = $this->chatModel->historyChat($idUser,$_COOKIE['idUser']);

                $formSendMsg = TRUE;
                $buttonBack = TRUE;

            }

        }

        return ['toRender' => [
            'title' => $title,
            'buttonBack' => $buttonBack,
            'formSendMsg' => $formSendMsg,
            'to' => $idUser,
            'from' => $_COOKIE['idUser'],
            'loginUser' => $login,
            'arrayFeed' => $arrayFeed,
            'arrayUsers' => $arrayUsers,
        ]];
    }

    /**
     * Display list users
     *
     * @param $idUser id user.
     *
     * @return array list users.
     */
    public function listUsers($idUser): array
    {
        return $this->authModel->allRegUsers($idUser);
    }
}
