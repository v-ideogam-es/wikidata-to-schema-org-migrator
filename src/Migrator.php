<?php

namespace VideoGames;

class Migrator
{
    public $schemaOrg = [];

    public $wikidata;

    public function __construct($filename) {
        $file           = file_get_contents($filename);
        $this->wikidata = json_decode($file);

        dump($this->wikidata);
    }

    public function migrate() {
        foreach ($this->wikidata as $_key => $game) {
            if (!array_key_exists($game->name, $this->schemaOrg)) {
                $schemaGame = (object) [
                    '@context'     => 'http://schema.org',
                    '@type'        => 'VideoGame',
                    'gamePlatform' => ($game->platform === 'Sega Mega Drive') ? 'Sega Genesis' : $game->platform,
                    'inLanguage'   => 'en-US', // Hardcoded for now.
                    'name'         => $game->name,
                    'publisher'    => $game->publisher,
                    'sameAs'       => $game->_game
                ];

                if (isset($game->developer)) {
                    $schemaGame->author = (object) [
                        '@type' => 'Organization',
                        'name'  => $game->developer
                    ];
                }

                if (isset($game->game_mode)) {
                    $schemaGame->playMode = $game->game_mode;
                }

                $this->schemaOrg[$game->name] = $schemaGame;
            } else {
                $schemaGame = $this->schemaOrg[$game->name];

                if (isset($schemaGame->playMode) && is_string($schemaGame->playMode)) {
                    if ($schemaGame->playMode !== $game->game_mode) {
                        $schemaGame->playMode = [
                            $schemaGame->playMode,
                            $game->game_mode
                        ];
                    }
                }  elseif (isset($schemaGame->playMode) && is_array($schemaGame->playMode)) {
                    if (!in_array($game->game_mode, $schemaGame->playMode)) {
                        $schemaGame->playMode[] = $game->game_mode;
                    }
                }
            }
        }
    }
}
