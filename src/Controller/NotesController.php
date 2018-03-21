<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use App\Entity\Note;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Prefix;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

/**
 * Notes Resource controller.
 *
 * @Prefix("api")
 */
class NotesController extends FOSRestController
{
    /**
     * Note Repository.
     */
    private $noteRepository;

    /**
     * Construct.
     */
    public function __construct(NoteRepository $note_repository)
    {
      $this->noteRepository = $note_repository;
    }

    /**
     * Notes Index.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Notes index",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Note::class)
     *     )
     * )
     * @SWG\Parameter(
     *     name="user_id",
     *     in="query",
     *     type="integer",
     *     description="The user id to retrieve notes"
     * )
     * @SWG\Tag(name="notes")
     */
    public function getNotesAction(Request $request)
    {
      $notes = [];
      if ($request->get('user_id')) {
        $notes = $this->noteRepository->findBy(['user' => $request->get('user_id')]);
      }
      else {
        $notes = $this->noteRepository->findAll();
      }
      $view = $this->view($notes, 200);
      return $this->handleView($view);
    }
}
