<?php

namespace App\Http\Controllers;

use App\Hands;
use App\PossibleResult;
use Illuminate\Http\Request;

class Round extends Controller
{

    private $round = [];
    /**
     * Using the construct to build the round
     * Round constructor.
     * @param $round
     */
    public function __construct($round)
    {
        foreach ($round as $play) {
            $h = [];
            for ($i = 0; $i < Hands::NUMBERCARDS; $i++) {
                $object = 'c' . $i;
                $card = [
                    'val' => (int)$play->$object[0] != 0 ? (int)$play->$object[0] : $this->getValue($play->$object[0]),
                    'flush' => $play->$object[1]];
                $h[] = $card;
            }
            $this->round[] = $h;
        }
        return $this->round;
    }

    /**
     * Get value for the cardsare not numeric
     *
     * @param $key
     * @return mixed
     */
    public function getValue($key)
    {
        $map = ['T' => 10, 'J' => 11, 'Q' => 12, 'K' => '13', 'A' => 14];
        return $map[$key];
    }

    /**
     *  Getting values and number of suits of deck,
     * @return mixed
     */
    function play()
    {
        foreach ($this->round as $singleHand) {
            $flush = array_count_values(array_column($singleHand, 'flush'));
            $values = array_count_values(array_column($singleHand, 'val'));
            arsort($values);
            $score[] = $this->getResult($flush, $values, array_keys($values));
        }
        return $score;
    }

    /**
     * Getting the score
     *
     * @param $flush
     * @param $values
     * @param $keys
     * @return array
     */
    function getResult($flush, $values, $keys)
    {
        $checkResult = new \App\Http\Controllers\PossibleResult();
        foreach ($checkResult->results as $result) {
            $params=['flush'=> $flush,  'values'=>$values, 'keys'=> $keys];
            if (call_user_func_array([$checkResult, $result['function']], [$params])) {
                return ['score' => $result['score'], 'cards' => $keys];
            }
        }
        $this->handleError();
    }

    /**
     *  handle the error
     */
    function handleError()
    {
        //TODO handle the error
        var_dump("Error!!!!");
    }
}



