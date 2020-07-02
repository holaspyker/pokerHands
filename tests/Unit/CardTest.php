<?php

namespace Tests\Unit;

use App\Hands;
use App\Repository\Round;
use App\Repository\Win;
use App\Wins;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CardTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testIsRoyalFlush()
    {
        $this->truncate();
        $hand1 = ["TS", "JS", "QS", "KS", "AS"];
        $score = $this->helperTest($hand1);
        $this->assertEquals(10, $score[0]['score']);

    }

    public function testIsStraightFlush()
    {
        $this->truncate();
        $hand1 = ["TS", "JS", "QS", "KS", "9S"];
        $score = $this->helperTest($hand1);
        $this->assertEquals(9, $score[0]['score']);
    }

    public function testIsPoker()
    {
        $this->truncate();
        $hand1 = ["TS", "TC", "TH", "TD", "AD"];
        $score = $this->helperTest($hand1);
        $this->assertEquals(8, $score[0]['score']);
    }

    public function testIsFullHouse()
    {
        $this->truncate();
        $hand1 = ["TS", "AC", "TH", "AD", "AD"];
        $score = $this->helperTest($hand1);
        $this->assertEquals(7, $score[0]['score']);
    }

    public function testIsFlush()
    {
        $this->truncate();
        $hand1 = ["3S", "8S", "4S", "AS", "2S"];
        $score = $this->helperTest($hand1);
        $this->assertEquals(6, $score[0]['score']);
    }

    public function testIsStraight()
    {
        $this->truncate();
        $hand1 = ["AS", "2S", "4S", "5S", "3D"];
        $score = $this->helperTest($hand1);
        $this->assertEquals(5, $score[0]['score']);
    }

    public function testIsThree()
    {
        $this->truncate();
        $hand1 = ["4S", "JH", "TH", "JC", "JD"];
        $score = $this->helperTest($hand1);
        $this->assertEquals(4, $score[0]['score']);
    }

    public function testIsTwoPairs()
    {
        $this->truncate();
        $hand1 = ["4S", "JC", "TH", "JC", "TD"];
        $score = $this->helperTest($hand1);
        $this->assertEquals(3, $score[0]['score']);
    }

    public function testIsPair()
    {
        $this->truncate();
        $hand1 = ["9S", "JC", "TH", "KC", "TD"];
        $score = $this->helperTest($hand1);
        $this->assertEquals(2, $score[0]['score']);
    }

    public function testHighCard()
    {
        $this->truncate();
        $hand1 = ["9S", "2C", "TH", "KC", "AD"];
        $score = $this->helperTest($hand1);
        $this->assertEquals(1, $score[0]['score']);
    }

    public function testTiebreak()
    {
        $this->truncate();
        $hand1 = ["AD", "2C", "TH", "KC", "5C"];
        $hand2 = ["AS", "3D", "TS", "KS", "5D"];
        $index = 0;
        $score = $this->helperTest($hand1, $hand2);
        $this->assertEquals(1, $score[0]['score']);
        $this->assertEquals(1, $score[1]['score']);
        $won = $this->getWinner($score, $index);
        $this->assertEquals(1, $won);
    }

    public function testTiebreak2()
    {
        $this->truncate();
        $hand1 = ["AD", "5C", "AH", "2C", "5C"];
        $hand2 = ["AS", "5D", "AC", "5H", "4D"];
        $index = 0;
        $score = $this->helperTest($hand1, $hand2);
        $this->assertEquals(3, $score[0]['score']);
        $this->assertEquals(3, $score[1]['score']);
        $won = $this->getWinner($score, $index);
        $this->assertEquals(1, $won);
    }

    public function testTiebreak3()
    {
        $this->truncate();
        $hand1 = ["AD", "TC", "AH", "2C", "TC"];
        $hand2 = ["AS", "5D", "AC", "5H", "4D"];
        $index = 0;
        $score = $this->helperTest($hand1, $hand2);
        $this->assertEquals(3, $score[0]['score']);
        $this->assertEquals(3, $score[1]['score']);
        $won = $this->getWinner($score, $index);
        $this->assertEquals(0, $won);
    }

    public function testRoyalVsPoker()
    {
        $this->truncate();
        $hand1 = ["TS", "JS", "QS", "KS", "AS"];
        $hand2 = ["7S", "7C", "7H", "7D", "AD"];;
        $index = 0;
        $score = $this->helperTest($hand1, $hand2);
        $this->assertEquals(10, $score[0]['score']);
        $this->assertEquals(8, $score[1]['score']);
        $won = $this->getWinner($score, $index);
        $this->assertEquals(0, $won);
    }

    public function testFlushVsFull()
    {
        $this->truncate();
        $hand1 = ["TS", "4S", "QS", "KS", "AS"];
        $hand2 = ["TD", "AC", "TH", "AH", "AD"];
        $index = 0;
        $score = $this->helperTest($hand1, $hand2);
        $this->assertEquals(6, $score[0]['score']);
        $this->assertEquals(7, $score[1]['score']);
        $won = $this->getWinner($score, $index);
        $this->assertEquals(1, $won);
    }


    public function truncate()
    {
        Wins::query()->truncate();
        Hands::query()->truncate();
    }

    public function helperTest($hand1, $hand2 = null)
    {
        $index = 0;
        $n_player = 0;
        $n_player1 = 1;
        $handObject = new Hands();
        $round[$n_player] = $handObject->saveHand($hand1, $index, $n_player);
        if (!empty($hand2)) {
            $handObject1 = new Hands();
            $round[$n_player1] = $handObject1->saveHand($hand2, $index, $n_player1);
        }
        $roundRepository = new Round($round);
        $score = $roundRepository->play();
        return $score;
    }

    public function getWinner($score, $index)
    {
        $winController = new Win();
        $winController->handleScore($score, $index);
        $won = DB::table('wins')->select('player')->where('round_id', $index)->get();
        return $won[0]->player;
    }
}
