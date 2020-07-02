<?php

namespace App\Http\Controllers;

use App\Wins;

class WinsController extends Controller
{
    /**
     * Handle the score, which player win
     *
     * @param $score
     * @param $round
     */
    function handleScore($score, $round)
    {
        $max = 0;
        foreach ($score as $player => $item) {
            if ($item['score'] > $max) {
                $max = $item['score'];
                $n_player = $player;
            } elseif ($item['score'] == $max) {
                $tie = new PossibleResult();
                $n_player = $tie->handleTieBreak($score);

            }
        }
        $hand = $score[$n_player]['hand'];
        $win = new Wins();
        $win->saveWins($n_player, $round, $max, $score, $hand);

    }

}
