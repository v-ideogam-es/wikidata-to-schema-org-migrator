<?php

namespace VideoGames;

use VideoGames\Games\SchemaOrgGame;

class Migrator
{
    /** @var SchemaOrgGame[] */
    public $schemaOrgGames = [];

    public $wikidataGames;

    public function __construct($filename) {
        $file                = file_get_contents($filename);
        $this->wikidataGames = json_decode($file);
    }

    public function migrate() {
        foreach ($this->wikidataGames as $_key => $game) {
            if (!array_key_exists($game->name, $this->schemaOrgGames)) {
                $this->schemaOrgGames[$game->name] = new SchemaOrgGame($game);
            } else {
                $schemaGame = $this->schemaOrgGames[$game->name];

                if (isset($game->game_mode)) {
                    $schemaGame->setPlayMode($game->game_mode);
                }
            }
        }
    }
}
