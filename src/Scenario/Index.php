<?php

namespace Chat\Scenario;

use \Chat\Http\Request;
use \Chat\Inject;
use \Chat\Scenario;


/**
 * Implements scenarios of index page for authorized visitors.
 */
class Index implements Scenario
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
        $auth = new Auth();
        $arrayRegUsers = $auth->arrayAllRegUser($_COOKIE['login']);

        return ['toRender' => [
            'title' => 'Здравствуйте, ' . $_COOKIE['login'],
            'arrayRegUsers' => $arrayRegUsers,
        ]];
    }
}
