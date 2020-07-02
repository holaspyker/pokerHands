<?php


namespace App\Repository;


use App\Wins;

class Win
{

    /**
     * Handle the score, which player win
     *
     * @param $score
     * @param $round
     */
    function handleScore($score, $round)
    {
        $max = 0;
        foreach ($score as $player => $item) {
            if ($item['score'] > $max) {
                $max = $item['score'];
                $n_player = $player;
            } elseif ($item['score'] == $max) {
                $n_player = $this->handleTieBreak($score);
            }
        }
        $hand = $score[$n_player]['hand'];
        $win = new Wins();
        $win->saveWins($n_player, $round, $max, $score, $hand);

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
