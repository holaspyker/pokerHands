<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PossibleResult extends Model
{
    /**
     *
     * Getting the possibles results
     * @return array[]
     */
    static function getPossibleResult()
    {
        return [
            ['score' => 10, 'name' => 'Royal Flush', 'function' => 'isRoyalFlush' ],
            ['score' => 9, 'name' => 'Straight Flush', 'function' => 'isStraightFlush'],
            ['score' => 8, 'name' => 'Four of a kind', 'function' => 'isPoker'],
            ['score' => 7, 'name' => 'Full House', 'function' => 'isFullHouse'],
            ['score' => 6, 'name' => 'Flush', 'function' => 'isFlush'],
            ['score' => 5, 'name' => 'Straight', 'function' => 'isStraight'],
            ['score' => 4, 'name' => 'Three of Kind', 'function' => 'isThree'],
            ['score' => 3, 'name' => 'Two Pairs', 'function' => 'isTwoPairs'],
            ['score' => 2, 'name' => 'One Pair', 'function' => 'isOnePair'],
            ['score' => 1, 'name' => 'High Card', 'function' => 'isHighCard' , 'params'],
        ];







    }






}
