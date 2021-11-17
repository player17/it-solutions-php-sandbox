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
        $title = 'Auth page';

        $post = $req->POST->getAll();
        if($post != [] && is_array($post)) {
            $visibleForm = false;

            $login = $req->POST->String('login');
            $pass = $req->POST->passwordHash('pass');

            $modelAuth = new Model\Auth();
            $title = $modelAuth->checkUserInBD($login);
            if($title == 'Логин занят') {
                $visibleForm = true;
            }

        }
        return ['toRender' => [
            'title' => $title,
            'form' => $visibleForm,
        ]];
    }


}

