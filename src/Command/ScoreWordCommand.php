<?php

namespace App\Command;

use GuzzleHttp\RequestOptions;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use GuzzleHttp\Client;

#[AsCommand(
    name: 'score-word',
    description: 'Score entered word on the "checkWord" API',
)]
class ScoreWordCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('score-word')
            ->setDescription('Score entered word on the "checkWord" API')
            ->addArgument('word', InputArgument::REQUIRED, 'The word to score');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $word = $input->getArgument('word');

        if ($word) {
            $io->info(sprintf('Word entered for scoring is: %s', $word));
        }

        $score = $this->scoreWord($word);

        if(is_numeric($score)) {
            $io->success("Score for entered word is: " . $score);
        } else {
            $io->error($score);
        }

        return Command::SUCCESS;
    }

    private function scoreWord($word)
    {
        $client = new Client([RequestOptions::VERIFY => false]);
        $apiUrl = 'https://127.0.0.1:8000/api/checkWord';

        $payload = [
            'word' => $word
        ];

        try {
            $response = $client->post($apiUrl, [
                'json' => $payload
            ]);
            
            $responseData = json_decode($response->getBody(), true);
            return $responseData['score'];
        } catch (\Exception $e) {
            $status_code = $e->getCode();
            if($status_code === 404) {
                return "Entered word doesn't exist in our dictionary";
            } else {
                return 'Error: ' . $e->getMessage();
            }
        }
    }
}