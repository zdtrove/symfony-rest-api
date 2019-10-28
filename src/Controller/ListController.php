<?php

namespace App\Controller;

use App\Repository\TaskListRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class ListController extends AbstractFOSRestController
{
    public function __construct(TaskListRepository $taskListRepository) {
        $this->taskListRepository = $taskListRepository;
    }
    public function getListsAction() {
        return $this->taskListRepository->findAll();
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
