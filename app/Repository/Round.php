<?php


namespace App\Repository;


use App\Hands;

class Round
{

    private $round = [];

    /**
     * Using the construct to build the round
     * Round constructor.
     * @param $round
     */
    public function __construct($round)
    {
        foreach ($round as $hand) {
            $h = [];
            for ($i = 0; $i < Hands::NUMBERCARDS; $i++) {
                $object = 'c' . $i;
                $card = [
                    'val' => (int)$hand->$object[0] != 0 ? (int)$hand->$object[0] : $this->getValue($hand->$object[0]),
                    'flush' => $hand->$object[1]];
                $h[] = $card;
            }
            $h['hand_id'] = $hand->id;
            $this->round[] = $h;
        }
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
            $score[] = $this->getResult($flush, $values, array_keys($values), $singleHand['hand_id']);
        }
        return $score;
    }

    /**
     *  Getting the score
     *
     * @param $flush
     * @param $values
     * @param $keys
     * @param $hand_id
     * @return array
     */
    function getResult($flush, $values, $keys, $hand_id)
    {
        $results = \App\PossibleResult::getPossibleResult();;
        foreach ($results as $result) {
            $params = ['flush' => $flush, 'values' => $values, 'keys' => $keys];
            $checkResult = new Result($flush, $values, $keys);

            if (call_user_func_array([$checkResult, $result['function']], [])) {
                return ['score' => $result['score'], 'flush' => $flush, 'cards' => $keys, 'hand' => $hand_id];
            }
        }
        return $this->handleError();
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
