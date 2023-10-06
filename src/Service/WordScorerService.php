<?php
namespace App\Service;

class WordScorerService
{
  public function scoreWord($word)
  {
    $isPalindrome = true;
    $isAlmostPalindrome = true;
    $tryInOtherDirection = false;
    $letters = [];
    $i = 0;

    for ($j = 0; $j < strlen($word); $j++) {
      $i++;
      $letter = $word[$j];
      $letters[$letter] = true;

      if ($isPalindrome && ($i - 1) < (strlen($word) / 2) && $letter !== $word[strlen($word) - $i]) {
        $isPalindrome = false;
        $i++;
      }

      if (!$isPalindrome && $isAlmostPalindrome && ($i - 1) <= (strlen($word) / 2) && $letter !== $word[strlen($word) - $i]) {
        if ($tryInOtherDirection) {
          $isAlmostPalindrome = false;
        }
        $tryInOtherDirection = true;
        $i = $i - 2;
      }
    }

    return count($letters) + ($isPalindrome ? 3 : 0) + (!$isPalindrome && $isAlmostPalindrome ? 2 : 0);
  }
}
?>