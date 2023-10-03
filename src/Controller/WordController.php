<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WordRepository;

class WordController extends AbstractController
{

  /**
   * @Route("/word-form", name="word_form")
   */
  public function wordForm(Request $request, WordRepository $wordRepository)
  {
    if ($request->isMethod('POST')) {
      $submittedWord = $request->request->get('word');

      dump($submittedWord);

      // Check if the submitted word exists in the database
      $existingWord = $wordRepository->findByWord($submittedWord);

      if (!$existingWord) {
        $this->addFlash('error', 'The word is not valid.');
      } else {
        // Word is valid, you can continue with scoring logic
        // ...
      }
    }

    return $this->render('word/form.html.twig');
  }

}
?>