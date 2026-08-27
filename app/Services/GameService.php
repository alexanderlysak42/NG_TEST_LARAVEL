<?php

namespace App\Services;

class GameService
{
    public function play(): array
    {
        $number = random_int(1, 1000);
        $isWin = $number % 2 === 0;
        $amount = $isWin ? $this->calculateAmount($number) : 0.0;

        return [
            'number' => $number,
            'result' => $isWin ? 'win' : 'lose',
            'amount' => $amount,
        ];
    }

    private function calculateAmount(int $number): float
    {
        $percentage = match (true) {
            $number > 900 => 0.70,
            $number > 600 => 0.50,
            $number > 300 => 0.30,
            default => 0.10,
        };

        return round($number * $percentage, 2);
    }
}
