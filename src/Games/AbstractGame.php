<?php

namespace VideoGames\Games;

abstract class AbstractGame
{
    public function __set($property, $value) {
        $properties = array_keys(get_object_vars($this));

        if (!in_array($property, $properties)) {
            return;
        }

        if (is_string($this->{$property}) && $this->{$property} !== $value) {
            $this->{$property} = [$this->{$property}, $value];

            return;
        }

        if (is_array($this->{$property}) && !in_array($value, $this->{$property})) {
            $this->{$property}[] = $value;
        }
    }
}
