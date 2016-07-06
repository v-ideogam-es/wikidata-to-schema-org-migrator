<?php

namespace spec\VideoGames\Games;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;

class SchemaOrgGameSpec extends ObjectBehavior
{
    function let()
    {
        $wikidataGame = (object) [
            'name'      => '',
            'platform'  => '',
            'publisher' => '',
            '_game'     => ''
        ];

        $this->beConstructedWith($wikidataGame);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('VideoGames\Games\SchemaOrgGame');
    }
}
