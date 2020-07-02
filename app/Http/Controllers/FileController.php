<?php

namespace App\Http\Controllers;

use App\Hands;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FileController extends Controller
{
    /**
     * Read the file
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:txt|max:2048',
        ]);
        $hands = file($request->file('file'));
        $this->readRounds($hands);
        return view('result');
    }

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
            $handObject =new Hands();
            $round[$n_player] = $handObject->saveHand($player, $index, $n_player);
            $n_player++;
        }
        $roundController = new Round($round);
        $score = $roundController->play();
        $this->handleScore($score, $index);
    }

    /**
     * Handle the score to know who won
     * @param $score
     * @param $index
     */
    function handleScore($score, $index)
    {
        $winController = new WinsController();
        $winController->handleScore($score, $index);
    }
}
