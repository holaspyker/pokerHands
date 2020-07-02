<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Wins extends Model
{

    /**
     * Getting how many hands won for each player
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getWinners()
    {
        return DB::table('wins')->select('player', DB::raw('count(*) as total'))->groupBy('player')->get();
    }

    /**
     * @param $player_id
     * @param $index
     * @param $max
     * @param $score
     * @param $hand
     */
    function saveWins($player, $round, $max, $score, $hand)
    {
        $this->player = $player;
        $this->round_id = $round;
        $this->score = $max;
        $this->all_hands = json_encode($score);//Saving all hands
        $this->hand_id = $hand;
        $this->save();
    }
}
