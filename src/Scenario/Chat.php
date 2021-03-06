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

    public function __construct()
    {
        $this->chatModel = new Model\Chat();
    }

    public function run(Request $req): array
    {
        $title = 'Чат';
        $buttonBack = FALSE;
        $formSendMsg = FALSE;
        $post = $req->GET->getAll();
        if($post != [] && is_array($post)) {
            if($req->GET->String('typeForm') == 'search') {
                $login = $req->GET->String('login');

                if($idUser = $this->chatModel->checkLogin($login)) {

                    $title = 'Чат с пользователем ' . $login;

                    $arrayFeed = [];

                    $arrayFeed = $this->chatModel->historyChat($idUser,$_COOKIE['idUser']);

                    $formSendMsg = TRUE;
                    $buttonBack = TRUE;

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

                $arrayFeed = [];

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
        ]];
    }
}
