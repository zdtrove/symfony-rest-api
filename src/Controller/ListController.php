<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TaskListRepository;
use App\Entity\TaskList;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Filesystem;

class ListController extends AbstractFOSRestController
{
    private $taskListRepository;
    private $entityManager;
    public function __construct(TaskListRepository $taskListRepository, EntityManagerInterface $entityManager) {
        $this->taskListRepository = $taskListRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    public function getListsAction() {
        $data = $this->taskListRepository->findAll();

        return $this->view($data, Response::HTTP_OK);
    }

    public function getListAction(int $id) {
        $data = $this->taskListRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * @Rest\RequestParam(name="title", description="Title of the list", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @return \FOS\RestBundle\View\View
     */
    public function postListsAction(ParamFetcher $paramFetcher) {
        $title = $paramFetcher->get('title');
        if ($title) {
            $list = new TaskList();
            $list->setTitle($title);
            $this->entityManager->persist($list);
            $this->entityManager->flush();
            return $this->view($list, Response::HTTP_OK);
        }
        return $this->view(['title' => 'This cannot be null'], Response::HTTP_BAD_REQUEST);
    }

    public function getListTasksAction(int $id) {

    }

    public function putListsAction() {

    }

    /**
     * @Rest\FileParam(name="image", description="The background of the list", nullable=false, image=true)
     * @param Request $request
     * @param ParamFetcher $paramFetcher
     * @param int $id
     * @return int
     */
    public function backgroundListsAction(ParamFetcher $paramFetcher, Request $request, TaskList $list) {
        $currentBackground = $list->getBackground();
        if (!is_null($currentBackground)) {
            $filesystem = new Filesystem();
            $filesystem->remove(
                $this->getUploadsDir() . $currentBackground
            );
        }
        $file = $paramFetcher->get('image');

        if ($file) {
            $filename = md5(uniquid()) . '.' . $file->guessClientExtension();
            $file->move(
                $this->getUploadsDir(),
                $filename
            );
            $list->setBackground($filename);
            $list->setBackgroundPath('/uploads/' . $filename);
            $this->entityManager->persist($list);
            $this->entityManager->flush();
            $data = $request->getUriForPath(
                $list->getBackgroundPath()
            );
            return $this->view($data, Response::HTTP_OK);
        }
        return $this->view(['message' => 'Something went wrong'], Response::HTTP_BAD_REQUEST);
    }

    private function getUploadsDir() {
        return $this->getParameter('uploads_dir');
    }
}
