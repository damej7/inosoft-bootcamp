<?php

namespace App\Http\Controller;

use App\ContohBootcamp\Services\TaskService;
use App\Helpers\MongoModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
	private TaskService $taskService;
	public function __construct()
	{
		$this->taskService = new TaskService();
	}

	public function showTasks()
	{
		$tasks = $this->taskService->getTasks();
		return response()->json($tasks);
	}

	public function createTask(Request $request)
	{
		$request->validate([
			'title' => 'required|string|min:3',
			'description' => 'required|string'
		]);

		$data = [
			'title' => $request->post('title'),
			'description' => $request->post('description')
		];

		$dataSaved = [
			'title' => $data['title'],
			'description' => $data['description'],
			'assigned' => null,
			'subtasks' => [],
			'created_at' => time()
		];

		$id = $this->taskService->addTask($dataSaved);
		$task = $this->taskService->getById($id);

		return response()->json($task);
	}


	public function updateTask(Request $request)
	{
		$request->validate([
			'task_id' => 'required|string',
			'title' => 'string',
			'description' => 'string',
			'assigned' => 'string',
			'subtasks' => 'array',
		]);

		$taskId = $request->post('task_id');
		$formData = $request->only('title', 'description', 'assigned', 'subtasks');
		$task = $this->taskService->getById($taskId);

		$this->taskService->updateTask($task, $formData);

		$task = $this->taskService->getById($taskId);

		return response()->json($task);
	}


	// TODO: deleteTask()
	public function deleteTask(Request $request)
	{
		$request->validate([
			'task_id' => 'required'
		]);

		$taskId = $request->task_id;

		$existTask = $this->taskService->destroyTaskId($taskId);

		if ($existTask != false) {
			return response()->json([
				"message" => "Task dengan ID " . $taskId . " telah di hapus."
			]);
		} else {
			return response()->json([
				"message" => "Task ID dengan ID " . $taskId . " tidak di temukan silahkan coba lagi."
			]);
		}
	}

	// TODO: assignTask()
	public function assignTask(Request $request)
	{
		$request->validate([
			'task_id' => 'required',
			'assigned' => 'required'
		]);

		$taskId = $request->get("task_id");
		$assignedName = $request->only("assigned");
		$existTask = $this->taskService->getById($taskId);

		if (!$existTask) {
			return response()->json([
				"message" => "Task ID dengan ID : " . $taskId . " tidak ditemukan silahkan coba lagi."
			]);
		} else {
			$this->taskService->assignedTask($existTask, $assignedName);
			$task = $this->taskService->getById($taskId);
			return response()->json($task);
		}
	}

	// TODO: unassignTask()
	public function unassignTask(Request $request)
	{
		$request->validate([
			'task_id' => 'required',
		]);

		$taskId = $request->get("task_id");
		$existTask = $this->taskService->getById($taskId);

		if (!$existTask) {
			return response()->json([
				"message" => "Task ID dengan ID : " . $taskId . " tidak ditemukan silahkan coba lagi."
			]);
		} else {
			$this->taskService->unassignedTask($existTask);
			$task = $this->taskService->getById($taskId);
			return response()->json($task);
		}
	}

	// TODO: createSubtask()
	public function createSubtask(request $request)
	{
		$request->validate([
			'task_id' => 'required',
			'title' => 'required|string',
			'description' => 'required|string'
		]);

		$taskId = $request->post('task_id');
		$title = $request->post('title');
		$description = $request->post('description');
		$existTask = $this->taskService->getById($taskId);

		if (!$existTask) {
			return response()->json([
				"message" => "Task ID dengan ID : " . $taskId . " tidak ditemukan silahkan coba lagi."
			]);
		} else {
			$this->taskService->createdSubtask($taskId, $title, $description);
			$task = $this->taskService->getById($taskId);
			return response()->json($task);
		}
	}

	// TODO deleteSubTask()
	public function deleteSubtask(Request $request)
	{
		$request->validate([
			'task_id' => 'required',
			'subtask_id' => 'required'
		]);

		$taskId = $request->post('task_id');
		$subtaskId = $request->post('subtask_id');

		$existTask = $this->taskService->getById($taskId);

		if (!$existTask) {
			return response()->json([
				"message" => "Task ID dengan IDDD : " . $taskId . "dan ID Subtasknya : " . $subtaskId . " tidak ditemukan silahkan coba lagi."
			]);
		} else {
			$this->taskService->deletedSubtask($taskId, $subtaskId);
			$task = $this->taskService->getById($taskId);
			return response()->json($task);
		}
	}
}
