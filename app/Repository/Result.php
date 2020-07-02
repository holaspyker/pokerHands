<?php


namespace App\Repository;


class Result
{
    private $flush;
    private $values;
    private $keys;

    public function __construct($flush, $values, $keys)
    {
        $this->flush = $flush;
        $this->values = $values;
        $this->keys = $keys;
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
    public function returnFlush($flush)
    {
        return count($flush) == 1;
    }

    /**
     * isRoyalFlush checking if it is straight, flush, and the smallest is a 10
     *
     * @return bool
     */
    public function isRoyalFlush()
    {
        sort($this->keys);
        return $this->straight($this->values) && $this->returnFlush($this->flush) && $this->keys[0] == 10;
    }

    /**
     * Checking if it is straight, flush
     * @return bool
     */
    public function isStraightFlush()
    {
        return $this->straight($this->values) && $this->returnFlush($this->flush);
    }

    /*
     * Checking if it is a poker
     * @return bool
     */
    public function isPoker()
    {
        return $this->values[$this->keys[0]] == 4;
    }

    /*
      * fullhouse 3 +2
      * @return bool
      */
    public function isFullHouse()
    {
        return $this->values[$this->keys[0]] == 3 && $this->values[$this->keys[1]] == 2;
    }

    /**
     *  all cards hqve the same suit of deck,
     * @return bool
     */
    public function isFlush()
    {
        return $this->returnFlush($this->flush);
    }

    /**
     * all cards are consecutive
     *
     * @return bool
     */
    public function isStraight()
    {
        return $this->straight($this->values);
    }

    /**
     * 3cards with the same value
     *
     * @return bool
     */
    public function isThree()
    {
        return $this->values[$this->keys[0]] == 3;
    }

    /**
     * 2+2 cards
     *
     * @return bool
     */
    public function isTwoPairs()
    {
        return $this->values[$this->keys[0]] == 2 && $this->values[$this->keys[1]] == 2;
    }

    public function isOnePair()
    {
        return $this->values[$this->keys[0]] == 2;
    }

    /**
     * The highest card
     * @return bool
     */
    public function isHighCard()
    {
        return count($this->values) == 5;
    }

}
