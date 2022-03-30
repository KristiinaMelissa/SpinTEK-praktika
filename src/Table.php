<?php

namespace KristiinaMelissa\SpinTekPraktika;

require 'vendor/autoload.php';

use KristiinaMelissa\SpinTekPraktika\FileWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;



class Table extends Command
{
    /**
     * The name of the command (the part after "php bin/console.php").
     *
     * @var string
     */
    protected static $defaultName = 'tabel';

    /**
     * The command description shown when running "php bin/console.php list".
     *
     * @var string
     */
    protected static $defaultDescription = 'Palga maksmise tabel.';

    /**
     * Execute the command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int 0 if everything went fine, or an exit code.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);

        $year = $io->ask('Sisesta aastaarv vahemikus 1920 - 2120:', 2022, function ($input){
            if (1920 > intval($input) || 2120 < intval($input)){
                throw new \RuntimeException("Aastaarv peab olema täisarv vahemikus 1920 kuni 2120.");
            }

            return intval($input);
        });

        $this->writeToFile($io, $year);

        if ($io->choice('Kas käivitada programm uuesti?', ['jah', 'ei'], 'jah') === 'jah') {
            return $this->execute($input, $output);
        }

        return Command::SUCCESS;
    }

    protected function writeToFile($io, $year){
        $fileWriter = new FileWriter($year);
        $fileNotExisting = $fileWriter->writeCSV();

        if ($fileNotExisting){
            $io->success(sprintf('Fail %s.csv loodi asukohta SpinTEK-praktika/lib/files!', $year));
        }else{
            $answer = $io->choice(sprintf('Fail %s.csv juba eksisteerib asukohas SpinTEK-praktika/lib/files! Kas soovite selle üle kirjutada?', $year),
                ['jah', 'ei'], 'jah');
            if ($answer === 'jah'){
                $fileWriter->writeCSV(true);
                $io->success(sprintf('Fail %s.csv kirjutati üle asukohas SpinTEK-praktika/lib/files!', $year));
            }
        }
    }
}
