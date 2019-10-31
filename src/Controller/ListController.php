<?php

namespace App\Controller;

use App\Repository\TaskListRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;

class ListController extends AbstractFOSRestController
{
    private $taskListRepository;
    public function __construct(TaskListRepository $taskListRepository) {
        $this->taskListRepository = $taskListRepository;
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    public function getListsAction() {
        $data = $this->taskListRepository->findAll();
        return $this->view($data, Response::HTTP_OK);
    }

    public function getListAction(int $id) {

    }

    public function postListsAction() {

    }

    public function getListTasksAction(int $id) {

    }

    public function putListsAction() {

    }

    public function backgroundListsAction(int $id) {

    }
}
