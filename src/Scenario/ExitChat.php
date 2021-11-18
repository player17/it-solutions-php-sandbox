<?php

namespace Chat\Scenario;

use \Chat\Http\Request;
use \Chat\Http\Params;
use \Chat\Inject;
use \Chat\Injector;
use \Chat\Scenario;
use \Chat\Model;

class ExitChat
{
    use Inject\HtmlRenderer;

    /**
     * Runs scenario of exit page.
     *
     * @param Request $req      HTTP request to index page.
     *
     */
    public function run(Request $req): array
    {
        foreach ($_COOKIE as $key => $val) {
            setcookie($key, $val, time()-86400);
        }
        header('Location: /');
    }
}
