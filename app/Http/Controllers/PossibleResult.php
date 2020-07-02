<?php

namespace App\Http\Controllers;


class PossibleResult extends Controller
{
    public $results;

    public function __construct()
    {
        $this->results = \App\PossibleResult::getPossibleResult();
        return $this->results;
    }

    /**
     * Check if it is an straight
     *
     * @param $values
     * @return bool
     */
    private function straight($values)
    {
        if (count($values) < 5) {
            return false;
        }
        $keys = array_keys($values);
        sort($keys);
        if (end($keys) - $keys[0] == 4) {
            return true;
        } elseif (end($keys) == 14) {
            return $this->straightWithAce($keys);
        }
        return false;
    }

    /**
     * Check the possibility A2345
     *
     * @param $keys
     * @return bool
     */
    private function straightWithAce($keys)
    {
        $newKeys = array_slice($keys, 0, -1);
        if (end($newKeys) - $newKeys[0] == 3 && end($newKeys) == 5) {
            return true;
        }
        return false;
    }

    /**
     * checking if the hand is flush
     *
     * @param $flush
     * @return bool
     */
    public function flush($flush)
    {
        return count($flush) == 1;
    }

    /**
     * isRoyalFlush checking if it is straight, flush, and the smallest is a 10
     * @param $params
     *
     * @return bool
     */
    public function isRoyalFlush($params)
    {
        sort($params['keys']);
        return $this->straight($params['values']) && $this->flush($params['flush']) && $params['keys'][0] == 10;

    }

    /**
     * Checking if it is straight, flush
     * @param $params
     * @return bool
     */
    public function isStraightFlush($params)
    {
        return $this->straight($params['values']) && $this->flush($params['flush']);
    }

    /*
     * Checking if it is a poker
     * @param $params
     * @return bool
     */
    public function isPoker($params)
    {
        return $params['values'][$params['keys'][0]] == 4;
    }

    /*
      * fullhouse 3 +2
      * @param $params
      * @return bool
      */
    public function isFullHouse($params)
    {
        return $params['values'][$params['keys'][0]] == 3 && $params['values'][$params['keys'][1]] == 2;
    }

    /**
     *  all cards hqve the same suit of deck,
     * @param $params
     * @return bool
     */
    public function isFlush($params)
    {
        return $this->flush($params['flush']);
    }

    /**
     * all cards are consecutive
     *
     * @param $params
     * @return bool
     */
    public function isStraight($params)
    {
        return $this->straight($params['values']);
    }

    /**
     * 3cards with the same value
     *
     * @param $params
     * @return bool
     */
    public function isThree($params)
    {
        return $params['values'][$params['keys'][0]] == 3;
    }

    /**
     * 2+2 cards
     *
     * @param $params
     * @return bool
     */
    public function isTwoPairs($params)
    {
        return $params['values'][$params['keys'][0]] == 2 && $params['values'][$params['keys'][1]] == 2;
    }

    public function isOnePair($params)
    {
        return $params['values'][$params['keys'][0]] == 2;
    }

    /**
     * The highest card
     * @param $params
     * @return bool
     */
    public function isHighCard($params)
    {
        return count($params['values']) == 5;
    }

    /**
     * Getting what palyer wins with the same score;
     *
     * @param $players
     * @return array|int|int[]|string
     */
    public function handleTieBreak($players)
    {
        $score = $players[0]['score'];
        switch ($score) {
            case 3 :
                $win = $this->hasImportantCards(2, $players);
                break;
            case 2 :
            case 6 :
            case 8 :
                $win = $this->hasImportantCards(1, $players);
                break;
            case 1 :
            case 9:
            case 4 :
                $win = $this->hasImportantCards(0, $players);
                break;
            case 10:
                $win = -1;
        }
        return $win;
    }

    /**
     * Checking who wins when has the same score
     *
     * @param $slice
     * @param $players
     * @return int
     */
    function hasImportantCards($slice, $players)
    {
        $cards = array_column($players, 'cards');
        $max = 0;
        $win = [];
        try {
            foreach ($cards as $player => $item) {
                $import = $slice > 0 ? array_slice($item, 0, $slice) : $item;
                arsort($import);
                if (max($import) > $max) {
                    $max = max($import);
                    $win = $player;
                } elseif (max($import) == $max) {
                    $new_players = $this->removeValue($players, $max);
                    $win = $this->hasImportantCards($slice - 1, $new_players);
                }
            }
        } catch (\Exception $e) {
            return -1; // same cards return error
        }

        return $win;
    }

    /**
     *  Remove value when both players have the same card
     *
     * @param $players
     * @param $max
     * @return mixed
     */
    function removeValue($players, $max)
    {
        foreach ($players as $new) {
            if (($key = array_search($max, $new['cards'])) !== false) {
                unset($new['cards'][$key]);
            }
            $new_values[] = $new;
        }
        return $new_values;
    }
}
