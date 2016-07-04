<?php

namespace VideoGames\Games;

use stdClass;
use JsonSerializable;

/**
 * @see https://schema.org/VideoGame
 */
class SchemaOrgGame implements JsonSerializable
{
    /** @var stdClass */
    public $author;

    /** @var string */
    public $gamePlatform;

    /** @var string */
    public $inLanguage = 'en-US';

    /** @var string */
    public $name;

    /** @var string|string[] */
    public $playMode;

    /** @var string */
    public $publisher;

    /** @var string */
    public $sameAs;

    public function __construct(stdClass $wikidataGame) {
        $this->gamePlatform  = ($wikidataGame->platform === 'Sega Mega Drive') ? 'Sega Genesis' : $wikidataGame->platform;
        $this->name          = $wikidataGame->name;
        $this->publisher     = $wikidataGame->publisher;
        $this->sameAs        = $wikidataGame->_game;

        if (isset($wikidataGame->developer)) {
            $this->setAuthor($wikidataGame->developer);
        }

        if (isset($wikidataGame->game_mode)) {
            $this->setPlayMode($wikidataGame->game_mode);
        }
    }

    public function jsonSerialize() {
        $properties    = get_object_vars($this);
        $schemaOrgGame = (object) [
            '@context' => 'http://schema.org',
            '@type'    => 'VideoGame'
        ];

        foreach ($properties as $propertyName => $propertyValue) {
            if ($propertyValue === null) {
                continue;
            }

            $schemaOrgGame->{$propertyName} = $propertyValue;
        }

        return $schemaOrgGame;
    }

    public function setAuthor($author) {
        $this->author = (object) [
            '@type' => 'Organization',
            'name'  => $author
        ];
    }

    public function setPlayMode($playMode) {
        /** @see https://schema.org/GamePlayMode */
        $map = [
            'cooperative gameplay'     => 'CoOp',
            'multiplayer video game'   => 'MultiPlayer',
            'single-player video game' => 'SinglePlayer',
        ];

        if (!isset($map[$playMode])) {
            return;
        }

        $playMode = $map[$playMode];

        if (!$this->playMode) {
            $this->playMode = $playMode;

            return;
        }

        if (is_string($this->playMode) && $this->playMode !== $playMode) {
            $this->playMode = [$this->playMode, $playMode];

            return;
        }

        if (is_array($this->playMode) && !in_array($playMode, $this->playMode)) {
            $this->playMode[] = $playMode;
        }
    }
}
