<?php

namespace spec\VideoGames\Games;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SchemaOrgGameSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('VideoGames\Games\SchemaOrgGame');
    }
}
