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
        $post = $req->POST->getAll();
        if($post != [] && is_array($post)) {
            if($req->POST->String('typeForm') == 'search') {
                $login = $req->POST->String('login');

                $modelChat = $this->chatModel;
                if($idUser = $modelChat->checkLogin($login)) {

                    $title = 'Чат с пользователем ' . $login;

                    $arrayFeed = [];

                    $arrayFeed = $modelChat->historyChat($idUser,$_COOKIE['idUser']);

                    $formSendMsg = TRUE;
                    $buttonBack = TRUE;

                } else {

                    $title = 'Нет такого пользователя';
                    $buttonBack = TRUE;

                }
            }
            if($req->POST->String('typeForm') == 'msg') {
                $login = $req->POST->String('loginUser');
                $title = 'Чат с пользователем ' . $login;

                $modelChat = $this->chatModel;
                $modelChat->setMsg(
                    $req->POST->String('to'),
                    $req->POST->String('from'),
                    $req->POST->String('textMsg')
                );

                $arrayFeed = [];

                $arrayFeed = $modelChat->historyChat($idUser,$_COOKIE['idUser']);

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
