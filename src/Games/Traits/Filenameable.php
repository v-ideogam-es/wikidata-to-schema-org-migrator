<?php

namespace VideoGames\Games\Traits;

trait Filenameable
{
    public function getFilename() {
        $chars        = str_split($this->name);
        $expandedWord = '';

        foreach ($chars as $index => $char) {
            if (ctype_upper($char) && $index > 0 && ctype_lower($chars[$index - 1])) {
                $expandedWord .= ' ';
            }

            $expandedWord .= $char;
        }

        $basename = str_slug($expandedWord);

        return "${basename}.jsonld";
    }
}
