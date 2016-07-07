<?php

namespace VideoGames;

use Illuminate\Support\Collection;
use VideoGames\Games\SchemaOrgGame;

class Migrator
{
    /** @var Collection */
    public $schemaOrgGames;

    /** @var Collection */
    public $wikidataGames;

    public function __construct($filename) {
        $file                 = file_get_contents($filename);
        $this->schemaOrgGames = collect([]);
        $this->wikidataGames  = collect(json_decode($file));
    }

    public function migrate() {
        $this->wikidataGames->each(function ($game) {
            if (!$this->schemaOrgGames->has($game->name)) {
                $this->schemaOrgGames->put($game->name, new SchemaOrgGame($game));
            } else {
                $schemaGame = $this->schemaOrgGames->get($game->name);

                if (isset($game->game_mode)) {
                    $schemaGame->setPlayMode($game->game_mode);
                }
            }
        });
    }
}
