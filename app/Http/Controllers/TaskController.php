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
     * @param string $project_id
     * @param TaskRepository $taskRepository
     * @return \Illuminate\Http\Response
     */
    public function index($project_id, TaskRepository $taskRepository)
    {
        $project = Project::find($project_id);

        if ($project->user_id === auth()->id()) {
            return response()->json($taskRepository->all($project_id));
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {
        $project = Project::find($project_id);

        if ($project->user_id === auth()->id()) {
            $task = $project->tasks()->save(Task::add($request->all(), false));
            return response()->json($task, 201);
        } else {
            return response()->json('Task not found.', 404);
        }
    }

    /**
     * Display the specified resource.
     *jj
     * @param int $id
     * @param string $project_id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $id)
    {
        $project = Project::find($project_id);

        if ($project->user_id === auth()->id()) {
            $task = $project->tasks()->find($id);
            if ($task) {
                return response()->json($task, 200);
            } else {
                return response()->json('Task not found.', 404);
            }
        } else {
            return response()->json('Task not found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param int $project_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $id)
    {
        $project = Project::find($project_id);

        if ($project->user_id === auth()->id()) {
            $task = $project->tasks()->find($id);
            if ($task) {
                $task->change($request->all());
                return response()->json('The task was updated.', 200);
            } else {
                return response()->json('Task not found.', 404);
            }
        } else {
            return response()->json('Task not found.', 404);
        }
    }

    public function checkToggle(Request $request, $project_id, $id)
    {
        $project = Project::find($project_id);

        if ($project->user_id === auth()->id()) {
            $task = $project->tasks()->find($id);
            $check = $request->check;
            if ($check) {
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
    public function destroy($project_id, $id)
    {
        $project = Project::find($project_id);

        if ($project->user_id === auth()->id()) {
            $task = $project->tasks()->find($id);
            if ($task) {
                $task->remove();
                return response()->json('The task was deleted.', 200);
            } else {
                return response()->json('Task not found.', 404);
            }
        } else {
            return response()->json('Task not found.', 404);
        }
    }

    public function indexWithModels($id, Request $request) {

        if ($id == auth()->id()) {
            $tasks = Task::with('project')->where('user_id', $id);
            if ($request->has('date_month')) {
                $date = new Carbon(urldecode($request->date_month));
                $start = $date->clone()->startOfMonth()->startOfWeek(Carbon::MONDAY);
                $end = $date->clone()->endOfMonth()->endOfWeek(Carbon::MONDAY);

                $tasks->whereBetween('deadline_date', [$start, $end]);
            } elseif ($request->has('date')) {
                $date = new Carbon(urldecode($request->date));
                $tasks->where(['deadline_date' => $date]);
            }
            return response()->json($tasks->get());
        }

        return response()->json('Tasks not found.', 404);
    }
}
