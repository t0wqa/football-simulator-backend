<?php


class Predictor
{

    protected $dataSet;

    protected $scoredByAllTeams = 0;

    protected $skipedByAllTeams = 0;

    protected $matchesTotal = 0;

    protected $teamsTotal = 0;

    protected $averageGoalsScoredPerMatch = 0;

    protected $averageGoalsSkipedPerMatch = 0;

    protected $averageAttackPower = 0;

    protected $averageDefensePower = 0;

    public function __construct($dataSet)
    {

        $this->dataSet = $dataSet;

        $this->init();
    }

    /**
     * @return void
     */
    private function init()
    {
        $this->teamsTotal = count($this->dataSet);

        foreach ($this->dataSet as $teamStatistics) {
            $this->scoredByAllTeams += $teamStatistics['goals']['scored'];
            $this->skipedByAllTeams += $teamStatistics['goals']['skiped'];
            $this->matchesTotal += $teamStatistics['games'];

            $this->averageGoalsScoredPerMatch += $teamStatistics['goals']['scored'] / $teamStatistics['games'];
            $this->averageGoalsSkipedPerMatch += $teamStatistics['goals']['skiped'] / $teamStatistics['games'];
        }

        $this->averageAttackPower = $this->averageGoalsScoredPerMatch / $this->teamsTotal;
        $this->averageDefensePower = $this->averageGoalsSkipedPerMatch / $this->teamsTotal;
    }

    /**
     * @param $c1
     * @param $c2
     * @return array|bool
     */
    public function match($c1, $c2)
    {
        $teamOne = $this->getTeam($c1);
        $teamTwo = $this->getTeam($c2);

        if (!$teamOne || !$teamTwo) {
            return false;
        }

        $teamOneResults = [];
        $teamTwoResults = [];

        $attempts = 5;

        for ($i = 0; $i < $attempts; $i++) {
            $results = $this->tryOneMatch($teamOne, $teamTwo);

            $teamOneResults[] = $results[0];
            $teamTwoResults[] = $results[1];
        }

        $teamOneTotalScore = 0;

        foreach ($teamOneResults as $teamOneResult) {
            $teamOneTotalScore += $teamOneResult;
        }

        $teamTwoTotalScore = 0;

        foreach ($teamTwoResults as $teamTwoResult) {
            $teamTwoTotalScore += $teamTwoResult;
        }

        return [
            round($teamOneTotalScore / $attempts),
            round($teamTwoTotalScore / $attempts),
        ];
    }

    /**
     * @param $teamStatistics
     * @return float|int
     */
    private function attackPower($teamStatistics)
    {
        return $teamStatistics['goals']['scored'] / $teamStatistics['games'] / $this->averageAttackPower;
    }

    /**
     * @param $teamStatistics
     * @return float|int
     */
    private function defensePower($teamStatistics)
    {
        return $teamStatistics['goals']['skiped'] / $teamStatistics['games'] / $this->averageDefensePower;
    }

    /**
     * @param $teamOne
     * @param $teamTwo
     * @return float|int
     */
    private function calculateMean($teamOne, $teamTwo)
    {
        return $this->attackPower($teamOne) * $this->defensePower($teamTwo) * $this->averageAttackPower;
    }

    /**
     * @param $teamOne
     * @param $teamTwo
     * @return array
     */
    private function getPoissonPossibilities($teamOne, $teamTwo)
    {
        $teamMean = $this->calculateMean($teamOne, $teamTwo);

        $res = array_map(function($el) use ($teamMean) {
            return pow(M_E, -$teamMean) * pow($teamMean, $el) / static::factorial($el) * 10000;
        }, range(0, 10));

        return $res;
    }

    /**
     * @param array $poissonPossibilities
     * @return array
     */
    private function getPoissonDistribution($poissonPossibilities)
    {
        $currentPos = 0;
        $newPoisson = [];

        foreach ($poissonPossibilities as $item) {
            $currentPos += $item;
            $newPoisson[] = $currentPos;
        }

        return $newPoisson;
    }

    /**
     * @param array $teamOne
     * @param array $teamTwo
     * @return array
     */
    private function predictMatchPossibilities($teamOne, $teamTwo)
    {
        $teamOnePoisson = $this->getPoissonDistribution(
            $this->getPoissonPossibilities($teamOne, $teamTwo)
        );

        $teamTwoPoisson = $this->getPoissonDistribution(
            $this->getPoissonPossibilities($teamTwo, $teamOne)
        );

        return [
            $teamOnePoisson,
            $teamTwoPoisson
        ];
    }

    /**
     * @param $teamOne
     * @param $teamTwo
     * @return array
     */
    private function tryOneMatch($teamOne, $teamTwo)
    {
        $poissonPossibilities = $this->predictMatchPossibilities($teamOne, $teamTwo);

        $teamOneRand = mt_rand(0, 9999);
        $teamOneResult = 0;

        for ($counterOne = 0; $counterOne < count($poissonPossibilities[0]); $counterOne++) {
            if ($teamOneRand <= $poissonPossibilities[0][$counterOne]) {
                $teamOneResult = $counterOne;
                break;
            }
        }

        $teamTwoRand = mt_rand(0, 9999);
        $teamTwoResult = 0;

        for ($counterTwo = 0; $counterTwo < count($poissonPossibilities[1]); $counterTwo++) {
            if ($teamTwoRand <= $poissonPossibilities[1][$counterTwo]) {
                $teamTwoResult = $counterTwo;
                break;
            }
        }

        return [$teamOneResult, $teamTwoResult];
    }

    private function getTeam($index)
    {
        return array_key_exists($index, $this->dataSet) ? $this->dataSet[$index] : false;
    }

    /**
     * @param $number
     * @return int
     */
    protected static function factorial($number)
    {
        $result = 1;

        for ($counter = 1; $counter <= $number; $counter++) {
            $result *= $counter;
        }

        return $result;
    }

}

//function match($c1, $c2)
//{
//    require_once 'data.php';
//
//    $predictor = new Predictor($data);
//    return $predictor->match(30, 0);
//}
//
//print_r(match(1, 2));
