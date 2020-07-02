<?php


namespace App\Repository;


use App\Hands;

class ParseFile
{
    /**
     * Read the round, and get the value of the cards
     *
     * @param $rounds
     */
    function readRounds($rounds)
    {
        for ($i = 1; $i < count($rounds); $i++) {
            $hand = explode(" ", $rounds[$i]);
            $this->parseRound($hand, $i);
        }
    }

    /**
     * Getting the hands ans send them and play
     *
     * @param $hand
     * @param $index
     */
    function parseRound($hand, $index)
    {
        $n_player = 0;
        foreach (array_chunk($hand, Hands::NUMBERCARDS) as $player) {
            $handObject = new Hands();
            $round[$n_player] = $handObject->saveHand($player, $index, $n_player);
            $n_player++;
        }
        $roundRepo = new \App\Repository\Round($round);
        $score = $roundRepo->play();
        $this->handleScore($score, $index);
    }

    /**
     * Handle the score to know who won
     * @param $score
     * @param $index
     */
    function handleScore($score, $index)
    {
        $winController = new Win();
        $winController->handleScore($score, $index);
    }

}
