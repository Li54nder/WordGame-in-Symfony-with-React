<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\WordRepository;
use App\Service\WordScorerService;

/**
 * @Route("/api", name="api")
 */
class WordController extends AbstractController
{

  private $entityManager;
  private $wordRepository;
  private $wordScorerService;

  public function __construct(EntityManagerInterface $entityManager, WordRepository $wordRepository, WordScorerService $wordScorerService)
  {
    $this->entityManager = $entityManager;
    $this->wordRepository = $wordRepository;
    $this->wordScorerService = $wordScorerService;
  }

  /**
   * @Route("/readAllWords", name="api_readAllWords")
   * @return JsonResponse
   */
  public function readAllWords()
  {
    return $this->json($this->wordRepository->findAll());
  }

  /**
   * @Route("/checkWord", name="api_checkWord")
   * @return JsonResponse
   */
  public function checkWord(Request $request)
  {
    try {
      $content = json_decode($request->getContent());

      if (!isset($content->word)) {
        throw new \InvalidArgumentException("Missing \"word\" in request JSON.", 422);
      }

      $word = $content->word;
      $found = (bool) $this->wordRepository->findByWord($word);

      if ($found) {
        $score = $this->wordScorerService->scoreWord($word);
        return $this->json([
          'word' => $word,
          'score' => $score
        ]);
      } else {
        throw new \Exception("Passed word in not in our dictionary.", 404);
      }
    } catch (\Throwable $e) {
      $statusCode = $e->getCode() ?: 500;
      return $this->json(['msg' => $e->getMessage()], $statusCode);
    }
  }

}
?>