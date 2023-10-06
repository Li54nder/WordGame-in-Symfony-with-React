<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class WordGameTest extends PantherTestCase
{
    public function testWordGame(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', 'https://localhost:8000/');

        // assertions for UI elements
        $this->assertSelectorTextContains('h2', 'Word Game');
        $this->assertGreaterThanOrEqual(1, $crawler->filter('form')->count());
        $this->assertGreaterThanOrEqual(1, $crawler->filter('input')->count());
        $this->assertGreaterThanOrEqual(1, $crawler->filter('button')->count());
        $this->assertGreaterThanOrEqual(1, $crawler->filter('a')->count());

        // 1st form submission (ALMOST PALINDROME)
        $form = $crawler->selectButton("Check Word")->form();
        $form['word']->setValue('test'); //this word needs to be in DB
        $client->submit($form);
        $client->wait()->until(
            \Facebook\WebDriver\WebDriverExpectedCondition::elementTextContains(
                \Facebook\WebDriver\WebDriverBy::xpath('//td'),
                'test'
            )
        );
        // now check score for submitted word, score should be: 5 (t, e, s = 3 (+2 because of "almost palindrome" [test -> tet]))
        $score1 = $crawler->filterXPath('//td[@id="score1"]');
        $this->assertSame('5', $score1->text());

        // 2nd form submission (PALINDROME)
        $form = $crawler->selectButton("Check Word")->form();
        $form['word']->setValue('rotor'); //this word needs to be in DB
        $client->submit($form);
        $client->wait()->until(
            \Facebook\WebDriver\WebDriverExpectedCondition::elementTextContains(
                \Facebook\WebDriver\WebDriverBy::xpath('//td'),
                'rotor'
            )
        );
        // now check score for submitted word, score should be: 6 (r, o, t = 3 (+3 because word is true palindrome))
        $score2 = $crawler->filterXPath('//td[@id="score2"]');
        $this->assertSame('6', $score2->text());

        // 3rd form submission (neither PALINDROME nor ALMOST PALINDROME)
        $form = $crawler->selectButton("Check Word")->form();
        $form['word']->setValue('word'); //this word needs to be in DB
        $client->submit($form);
        $client->wait()->until(
            \Facebook\WebDriver\WebDriverExpectedCondition::elementTextContains(
                \Facebook\WebDriver\WebDriverBy::xpath('//td'),
                'word'
            )
        );
        // now check score for submitted word, score should be: 4 (w, o, r, d = 4)
        $score3 = $crawler->filterXPath('//td[@id="score3"]');
        $this->assertSame('4', $score3->text());
    }
}
