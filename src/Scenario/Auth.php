<?php

namespace Chat\Scenario;

use \Chat\Http\Request;
use \Chat\Http\Params;
use \Chat\Inject;
use \Chat\Scenario;

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

        $post = $req->POST->getAll();
        if($post != [] && is_array($post)) {
            $visibleForm = false;
            print_r($post);
            echo 'Проверить базу и зарегать пользователя';
        }
        return ['toRender' => [
            'title' => 'Auth page',
            'form' => $visibleForm,
        ]];
    }


}

