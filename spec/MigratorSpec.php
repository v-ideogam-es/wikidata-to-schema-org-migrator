<?php

namespace spec\VideoGames;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MigratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('VideoGames\Migrator');
    }
}
