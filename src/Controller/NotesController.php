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
     * @SWG\Get(
     *     security={{"bearer":{}}},
     *     tags={"Notes"},
     *     @SWG\Response(
     *         response=200,
     *         description="Successful",
     *         @SWG\Schema(
     *             type="array",
     *             @Model(type=Note::class)
     *         )
     *     ),
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="query",
     *         type="integer",
     *         description="The user id to retrieve notes"
     *     )
     * )
     */
    public function getNotesAction(Request $request)
    {
      $notes = [];
      $user = $this->getUser();
      if (!$user->hasRole('ROLE_ADMIN')) {
        if (empty($request->get('user_id'))) {
          // Only admin can send request without user_id.
          throw $this->createAccessDeniedException('Unauthorized to see all notes.');
        }
        elseif ($request->get('user_id') != $user->getId()) {
          // Non admin users can only see own notes.
          throw $this->createAccessDeniedException('Unauthorized to see notes from other users.');
        }
      }

      if ($request->get('user_id')) {
        $notes = $this->noteRepository->findBy(['user' => $request->get('user_id')]);
      }
      else {
        $notes = $this->noteRepository->findAll();
      }
      $view = $this->view($notes, 200);
      return $this->handleView($view);
    }

    /**
     * Notes Retrieve.
     *
     * @SWG\Get(
     *     security={{"bearer":{}}},
     *     tags={"Notes"},
     *     @SWG\Response(
     *         response=200,
     *         description="Successful",
     *         @SWG\Schema(
     *             type="array",
     *             @Model(type=Note::class)
     *         )
     *     ),
     *     @SWG\Parameter(
     *         name="note_id",
     *         in="path",
     *         type="integer",
     *         description="The note id to retrieve"
     *     )
     * )
     */
    public function getNoteAction($note_id)
    {
      $user = $this->getUser();
      $note = $this->noteRepository->find($note_id);
      if (empty($note)) {
          // Note not found.
          throw $this->createNotFoundException('Note not found.');
      }
      if (!$user->hasRole('ROLE_ADMIN')) {
        if ($note->getUser()->getId() != $user->getId()) {
          // Only admin can see all notes.
          throw $this->createAccessDeniedException('Unauthorized to see this note.');
        }
      }

      $view = $this->view($note, 200);
      return $this->handleView($view);
    }

    /**
     * Notes POST.
     *
     * @SWG\Post(
     *     security={{"bearer":{}}},
     *     tags={"Notes"},
     *     @SWG\Response(
     *         response=201,
     *         description="Successful",
     *         @SWG\Schema(
     *             type="array",
     *             @Model(type=Note::class)
     *         )
     *     ),
     *     @SWG\Parameter(
     *         name="note",
     *         in="body",
     *         description="The note to create",
     *         type="Note::class",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                 property="title",
     *                 description="Note's title",
     *                 type="string",
     *             ),
     *             @SWG\Property(
     *                 property="body",
     *                 description="Note's body",
     *                 type="string",
     *             )
     *         )
     *     )
     * )
     */
    public function postNotesAction(Request $request)
    {
      $title = $request->get('title');
      $body = $request->get('body');
      $note = new Note();
      $note->setTitle($title);
      $note->setBody($body);
      $note->setUser($this->getUser());
      $em = $this->getDoctrine()->getManager();
      $em->persist($note);
      $em->flush();

      $view = $this->view($note, 201);
      return $this->handleView($view);
    }

    /**
     * Notes PUT.
     *
     * @SWG\Put(
     *     security={{"bearer":{}}},
     *     tags={"Notes"},
     *     @SWG\Response(
     *         response=201,
     *         description="Successfully created",
     *         @SWG\Schema(
     *             type="array",
     *             @Model(type=Note::class)
     *         )
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="Successfully updated"
     *     ),
     *     @SWG\Parameter(
     *         name="note",
     *         in="body",
     *         description="The note to create",
     *         type="Note::class",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                 property="id",
     *                 description="Note's id",
     *                 type="integer",
     *             ),
     *             @SWG\Property(
     *                 property="title",
     *                 description="Note's title",
     *                 type="string",
     *             ),
     *             @SWG\Property(
     *                 property="body",
     *                 description="Note's body",
     *                 type="string",
     *             )
     *         )
     *     )
     * )
     */
    public function putNotesAction(Request $request)
    {
      $note_id = $request->get('id');
      $response_code = 204;
      if ($note_id) {
        $user = $this->getUser();
        $note = $this->noteRepository->find($note_id);
        if (!empty($note)) {
          if (!$user->hasRole('ROLE_ADMIN')) {
            if ($note->getUser()->getId() != $user->getId()) {
              // Only admin can see all notes.
              throw $this->createAccessDeniedException('Unauthorized to see this note.');
            }
          }
        } else {
          throw $this->createNotFoundException('Note not found.');
        }
      }
      else {
        $note = new Note();
        $note->setUser($this->getUser());
        $response_code = 201;
      }
      $title = $request->get('title');
      $body = $request->get('body');
      $note->setTitle($title);
      $note->setBody($body);
      $em = $this->getDoctrine()->getManager();
      $em->persist($note);
      $em->flush();

      $view = $this->view($note, $response_code);
      return $this->handleView($view);
    }
}
