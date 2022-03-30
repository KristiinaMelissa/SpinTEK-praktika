<?php

namespace KristiinaMelissa\SpinTekPraktika;

@include(__DIR__ . 'files');


class FileWriter
{
    private int $year;
    private DateCalculator $dateCalculator;

    public function __construct($year)
    {
        $this->year = $year;
        $this->dateCalculator = new DateCalculator($this->year);
    }

    public function writeCSV($overwrite = false): bool {
        if (file_exists(sprintf(__DIR__.'/files/%s.csv', $this->year)) && !$overwrite){
            return false;
        }
        $file = fopen(sprintf(__DIR__.'/files/%s.csv', $this->year), 'w');
        fputcsv($file, ['meeldetuletuse_kuupaev', 'palga_maksmise_kuupaev']);
        for ($month = 1; $month <= 12; $month++){
            fputcsv($file, $this->getLine($month));
        }
        fclose($file);
        return true;
    }


    private function getLine($month): array {
        $line = [];
        array_push($line, $this->dateCalculator->getNotificationDate($month)->format('d/m/Y'), $this->dateCalculator->getPaymentDate($month)->format('d/m/Y'));
        return $line;
    }
}