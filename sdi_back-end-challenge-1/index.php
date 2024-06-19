<?php

class CarRental
{
    private $carOptions;

    public function __construct($carOptions)
    {
        $this->carOptions = $carOptions;
    }

    private $minCost = PHP_INT_MAX;
    private $minCombination = [];

    public function calculateOptimalCost($seats)
    {
        // Recursive function to find the best combination
        $this->findBestCombination($seats, [], 0);

        // Print results
        foreach ($this->minCombination as $combo) {
            echo $combo['size'] . ' x ' . $combo['count'] . PHP_EOL;
        }
        echo 'Total = PHP ' . $this->minCost . PHP_EOL;
    }

    private function findBestCombination($seats, $currentCombination, $currentCost)
    {
        if ($seats <= 0) {
            if ($currentCost < $this->minCost) {
                $this->minCost = $currentCost;
                $this->minCombination = $currentCombination;
            }
            return;
        }

        foreach ($this->carOptions as $option) {
            $newCombination = $currentCombination;
            $found = false;

            foreach ($newCombination as &$combo) {
                if ($combo['size'] == $option['size']) {
                    $combo['count']++;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $newCombination[] = ['size' => $option['size'], 'count' => 1];
            }

            $this->findBestCombination(
                $seats - $option['capacity'],
                $newCombination,
                $currentCost + $option['cost']
            );

            if ($currentCost + $option['cost'] >= $this->minCost) {
                break;
            }
        }
    }
}

// Define the car options dynamically
$carOptions = [
    ['size' => 'Small (S)', 'capacity' => 5, 'cost' => 5000],
    ['size' => 'Medium (M)', 'capacity' => 10, 'cost' => 8000],
    ['size' => 'Large (L)', 'capacity' => 15, 'cost' => 12000], 
];

// Main program
echo 'Please input number (seat): ';
$handle = fopen("php://stdin", "r");
$input = fgets($handle);
$seats = (int)trim($input);

$carRental = new CarRental($carOptions);
$carRental->calculateOptimalCost($seats);
