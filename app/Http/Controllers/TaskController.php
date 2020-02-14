<?php

namespace App\Http\Controllers;

use App\Project;
use App\Repositories\TaskRepository;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param TaskRepository $taskRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, TaskRepository $taskRepository)
    {
        $paramsKeys = ['project_id', 'date', 'date_month'];
        $user_id = auth()->user()->id;
        if (count($request->all()) === 0) {
            return response()->json($taskRepository->allByUser($user_id), 200);

        } elseif (count($request->all()) === 1) {
            if ($request->has($paramsKeys[0])) {
                $project_id = $request->get($paramsKeys[0]);
                $project = Project::find($project_id);

                if ($project->user_id !== $user_id) {
                    return response()->json('Access denied.', 400);
                }
                return response()->json($taskRepository->allByProject($project_id, $user_id));
            }

            if ($request->has($paramsKeys[1])) {
                return response()->json($taskRepository->allByDate($request->get($paramsKeys[1]), $user_id));
            }

            if ($request->has($paramsKeys[2])) {
                return response()->json($taskRepository->allByMonth($request->get($paramsKeys[2]), $user_id));
            }
        } else {
            return response()->json('Access denied.', 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $project_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (count($request->all()) === 1) {
            $task = Task::create(array_merge($request->all(), ['user_id' => auth()->id()]));
            return response()->json($task, 201);
        } else {
            if (count($request->all()) === 2 && $request->has('project_id')) {
                $task = Task::create(array_merge($request->all(), [
                    'user_id' => auth()->id(),
                    'project_id' => $request->get('project_id')
                ]));
                return response()->json($task, 201);
            } else {
                return response()->json('Access denied.', 400);
            }
        }
    }

    /**
     * Display the specified resource.
     *jj
     * @param int $id
     * @param TaskRepository $taskRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, TaskRepository $taskRepository)
    {
        $task = $taskRepository->getById($id);

        if ($task && $task->user_id === auth()->id()) {
            return response()->json($task, 200);
        } else {
            return response()->json('Task not found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if ($task && $task->user_id === auth()->id()) {
            $task->change($request->all());
            return response()->json('The task was updated.', 200);
        } else {
            return response()->json('Task not found.', 404);
        }
    }

    public function check($id)
    {
        $task = Task::find($id);

        if ($task && $task->user_id === auth()->id()) {
            if (!$task->is_done) {
                $task->check();
                return response()->json('The task checked.', 200);
            } else {
                $task->uncheck();
                return response()->json('The task unchecked.', 200);
            }
        }
        return response()->json('Task not found.', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param int $project_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if ($task && $task->user_id === auth()->id()) {
            $task->remove();
            return response()->json('The task was deleted.', 200);
        } else {
            return response()->json('Task not found.', 404);
        }
    }
}
