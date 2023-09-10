<?php

namespace App\ContohBootcamp\Services;

use App\ContohBootcamp\Repositories\TaskRepository;

class TaskService
{
	private TaskRepository $taskRepository;

	public function __construct()
	{
		$this->taskRepository = new TaskRepository();
	}

	/**
	 * NOTE: untuk mengambil semua tasks di collection task
	 */
	public function getTasks()
	{
		$tasks = $this->taskRepository->getAll();
		return $tasks;
	}

	/**
	 * NOTE: menambahkan task
	 */
	public function addTask(array $data)
	{
		$taskId = $this->taskRepository->create($data);
		return $taskId;
	}

	/**
	 * NOTE: UNTUK mengambil data task
	 */
	public function getById(string $taskId)
	{
		$task = $this->taskRepository->getById($taskId);
		return $task;
	}

	/**
	 * NOTE: untuk update task
	 */
	public function updateTask(array $editTask, array $formData)
	{
		if (isset($formData['title'])) {
			$editTask['title'] = $formData['title'];
		}

		if (isset($formData['description'])) {
			$editTask['description'] = $formData['description'];
		}

		$id = $this->taskRepository->save($editTask);
		return $id;
	}

	public function destroyTaskId(string $taskId)
	{
		$existTask = $this->taskRepository->getById($taskId);
		if ($existTask) {
			$this->taskRepository->destroyTaskId($taskId);
			return true;
		} else {
			return false;
		}
	}

	public function assignedTask(array $taskId, array $assignedName)
	{
		if (isset($assignedName['assigned'])) {
			$taskId['assigned'] = $assignedName['assigned'];
		}
		$id = $this->taskRepository->save($taskId);
		return array($id);
	}

	public function unassignedTask(array $taskId)
	{
		if (isset($taskId['assigned'])) {
			$taskId['assigned'] = null;
		}
		$id = $this->taskRepository->save($taskId);
		return array($id);
	}

	public function createdSubtask(string $taskId, string $title, string $description)
	{
		$taskId = $this->taskRepository->getById($taskId);
		$subtasks = isset($existTask['subtasks']) ? $existTask['subtasks'] : [];
		$subtasks[] = [
			'_id' => (string) new \MongoDB\BSON\ObjectId(),
			'title' => $title,
			'description' => $description
		];
		$taskId['subtasks'] = $subtasks;
		$id = $this->taskRepository->save($taskId);
		return array($id);
	}

	public function deletedSubtask(string $taskId, string $subtaskId)
	{
		$taskId = $this->taskRepository->getById($taskId);
		$subtasks = isset($existTask['subtasks']) ? $existTask['subtasks'] : [];
		$subtasks = array_filter($subtasks, function ($subtask) use ($subtaskId) {
			return $subtask["_id"] != $subtaskId;
		});
		$subtasks = array_values($subtasks);
		$taskId['subtasks'] = $subtasks;
		$id = $this->taskRepository->save($taskId);
		return $id;
	}
}
