<?php

namespace App\Http\Controllers;

use App\Wins;
use Illuminate\Http\Request;

class WinsController extends Controller
{
    /**
     * Handle the score, which player win
     *
     * @param $score
     * @param $index
     */
    function  handleScore($score, $index){
        $max = 0;
        foreach ($score as $player => $item) {
            if ($item['score'] > $max) {
                $max = $item['score'];
                $player_id = $player;
            } elseif ($item['score'] == $max) {
                $tie = new PossibleResult();
                $player_id = $tie->handleTieBreak($score);
            }
        }
        $win = New Wins();
        $win->saveWins($player_id , $index ,$max , $score);

    }

}
