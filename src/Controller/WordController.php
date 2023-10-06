<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\WordRepository;

/**
 * @Route("/api", name="api")
 */
class WordController extends AbstractController
{

  private $entityManager;
  private $wordRepository;

  public function __construct(EntityManagerInterface $entityManager, WordRepository $wordRepository)
  {
    $this->entityManager = $entityManager;
    $this->wordRepository = $wordRepository;
  }

  /**
   * @Route("/readAllWords", name="api_readAllWords")
   * @return JsonResponse
   */
  public function readAllWords()
  {
    $words = $this->wordRepository->findAll();
    $arrayOfWords = [];
    foreach ($words as $word) {
      $arrayOfWords[] = $word->toArray();
    }
    return $this->json($arrayOfWords);
  }

  /**
   * @Route("/checkWord", name="api_checkWord")
   * @return JsonResponse
   */
  public function testWord(Request $request)
  {
    try {
      $content = json_decode($request->getContent());

      if (!isset($content->word)) {
        throw new \InvalidArgumentException("Missing \"word\" in request JSON.", 422);
      }

      $word = $content->word;
      $found = (bool) $this->wordRepository->findByWord($word);

      if($found) {
        $score = $this->scoreWord($word);
        return $this->json([
          'word' => $word,
          'score' => $score
        ]);
      } else {
        throw new \Exception("Passed word in not in our dictionary.", 404);
      }
    } catch (\Throwable $e) {
      $statusCode = $e->getCode() ?: 500;
      // dump($e);
      // echo $e;
      $statusCode = $statusCode == 7? 500 : $statusCode;
      return $this->json(['msg' => $e->getMessage()], $statusCode);
    }
  }

  private function scoreWord($word)
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