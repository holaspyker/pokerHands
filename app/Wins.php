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
     * Save the model wins in the database
     *
     * @param $player
     * @param $index
     * @param $max
     * @param $score
     */
    function  saveWins($player_id , $index ,$max , $score)
    {
        $this->player = $player_id;
        $this->round_id = $index;
        $this->score = $max;
        $this->all_hands = json_encode($score);//Saving all hands
        $this->save();
    }
}
