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
    public function run(Request $req): array
    {
        return ['toRender' => [
            'title' => 'Чат',
        ]];
    }
}
