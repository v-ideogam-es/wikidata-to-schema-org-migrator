<?php

use VideoGames\Migrator;

require_once 'vendor/autoload.php';

$migrator = new Migrator('data.json');

$migrator->migrate();

if (!is_dir('./data')) {
    mkdir('data');
}

foreach ($migrator->schemaOrgGames as $game) {
    $filename = str_slug($game->name);
    $json     = json_encode($game, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

    dump($json);
    file_put_contents("./data/${filename}.jsonld", $json);
}