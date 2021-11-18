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

        $post = $req->POST->getAll();
        if($post != [] && is_array($post)) {

            if($req->POST->String('typeForm') =='auth') {
                $visibleForm = false;

                $login = $req->POST->String('login');
                $pass = $req->POST->passwordHash('pass');

                $modelAuth = new Model\Auth();
                if($idUser = $modelAuth->checkUserInBD($login, $pass)) {
                    $title = 'Здравствуй, ' . $login;
                    setcookie("AUTH", 'TRUE', time()+3600);
                    setcookie("idUser", $idUser, time()+3600);
                    setcookie("login", $login, time()+3600);
                    $searchLogin = true;
                } else {
                    $title = 'Есть такой пользователь';
                    $visibleForm = true;
                }
            } else if($req->POST->String('typeForm') =='enter') {

            }


        }
        return ['toRender' => [
            'title' => $title,
            'form' => $visibleForm,
            'searchLogin' => $searchLogin,
            'enterForm'=> $enterForm,
        ]];
    }


}

