<?php

namespace VideoGames\Games;

use DomainException;
use JsonSerializable;
use stdClass;
use VideoGames\Games\Traits\Filenameable;

/**
 * @see https://schema.org/VideoGame
 */
class SchemaOrgGame extends AbstractGame implements JsonSerializable
{
    use Filenameable;

    /** @var stdClass */
    public $author;

    /** @var string */
    public $gamePlatform;

    /** @var string */
    public $genre;

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
        $this->name      = $wikidataGame->name;
        $this->publisher = $wikidataGame->publisher;
        $this->sameAs    = $wikidataGame->_game;

        $this->setGamePlatform($wikidataGame->platform);

        if (isset($wikidataGame->developer)) {
            $this->setAuthor($wikidataGame->developer);
        }

        if (isset($wikidataGame->game_mode)) {
            $this->setPlayMode($wikidataGame->game_mode);
        }

        if (isset($wikidataGame->genre)) {
            $this->setGenre($wikidataGame->genre);
        }
    }

    /**
     * @link http://php.net/manual/en/class.jsonserializable.php
     */
    public function jsonSerialize() {
        $properties    = collect(get_object_vars($this));
        $schemaOrgGame = (object) [
            '@context' => 'http://schema.org',
            '@type'    => 'VideoGame'
        ];

        $properties->filter(function ($value) {
            return $value !== null;
        })->each(function ($value, $property) use ($schemaOrgGame) {
            $schemaOrgGame->{$property} = $value;
        });

        return $schemaOrgGame;
    }

    public function setAuthor(string $author) {
        $this->author = (object) [
            '@type' => 'Organization',
            'name'  => $author
        ];
    }

    public function setGamePlatform(string $gamePlatform) {
        // Temporariliy translating "Mega Drive" to "Genesis" since we're only
        // migrating US games right now.
        $map = collect([
            'Sega Mega Drive' => 'Sega Genesis'
        ]);

        if (!$map->has($gamePlatform)) {
            return;
        }

        $this->gamePlatform = $map->get($gamePlatform);
    }

    public function setGenre(string $genre) {
        $blacklist = collect([
            'isometric graphics in video games and pixel art'
        ]);

        if ($blacklist->contains($genre)) {
            return;
        }

        $map = collect([
            'action game'                => 'Action',
            'action role-playing game'   => 'Action-adventure',
            "beat 'em up"                => "Beat 'em up",
            'fighting game'              => 'Fighting',
            'first-person shooter'       => 'First-person shooter',
            'pinball'                    => 'Pinball',
            'platform game'              => 'Platformer',
            'puzzle'                     => 'Puzzle',
            'racing video game'          => 'Racing',
            'role-playing video game'    => 'Role-playing',
            "shoot 'em up"               => "Shoot 'em up",
            'sports video game'          => 'Sports',
            'tactical role-playing game' => 'Tactical role-playing'
        ]);

        if (!$map->has($genre)) {
            throw new DomainException("Unknown genre: ${genre}");
        }

        $this->set('genre', $map->get($genre));
    }

    public function setPlayMode(string $playMode) {
        /** @see https://schema.org/GamePlayMode */
        $map = collect([
            'cooperative gameplay'     => 'CoOp',
            'multiplayer video game'   => 'MultiPlayer',
            'single-player video game' => 'SinglePlayer',
        ]);

        if (!$map->has($playMode)) {
            throw new DomainException("Unknown play mode: ${playMode}");
        }

        $this->set('playMode', $map->get($playMode));
    }
}
