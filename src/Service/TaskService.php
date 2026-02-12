<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    private TaskRepository $taskRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        TaskRepository $taskRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
    }

    public function addTask(Task $task): void
    {
        if (strlen($task->getTitle()) < 3) {
            throw new \Exception("Le titre doit contenir au moins 3 caractères.");
        }

        if (empty($task->getContent())) {
            throw new \Exception("Le contenu est obligatoire.");
        }

        $task->setStatus(false);

        try {
            $this->entityManager->persist($task);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de l'enregistrement de la tâche.");
        }
    }

    public function getAllTasks(): array
    {
        try {
            $tasks = $this->taskRepository->findAll();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération des tâches.");
        }

        if (empty($tasks)) {
            throw new \Exception("Aucune tâche trouvée.");
        }

        return $tasks;
    }
}
