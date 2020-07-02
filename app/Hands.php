<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hands extends Model
{
    const  NUMBERCARDS = 5;

    /**
     *
     * Saving  Hand model in DB
     * @param $player
     * @param $index
     * @param $n_player
     * @return $this
     */
    function saveHand($player, $index, $n_player)
    {
        for ($i = 0; $i < count($player); $i++) {
            $var = "c$i";
            $this->$var = $player[$i];
        }
        $this->player = $n_player;
        $this->round_id = $index;
        $this->save();
        return $this;
    }
}
