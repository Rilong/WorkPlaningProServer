<?php

namespace App\Http\Controllers;

use App\Project;
use App\Repositories\ProjectRepository;
use App\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $isTasks = false;

        $allProjects = auth()->user()->projects();

        if ($request->has('tasks') && $request->get('tasks') == 1){
            $isTasks = true;
            $allProjects->with('tasks');
        }

        $allProjects = $allProjects->get()->toArray();

        if ($isTasks) {
            $allProjects = array_map(function ($arr) {
                unset($arr['tasks']);
                return $arr;
            }, $allProjects);
        }
        return response()->json($allProjects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (strlen(trim($request->get('name'))) === 0) {
            return response()->json('The field name is empty.', 400);
        }

        $project = Project::add(['name' => $request->get('name')]);
        auth()->user()->projects()->save($project);
        return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $project_id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id)
    {
        $project = auth()->user()->project()->find($project_id);
        if ($project) {
            return response()->json($project);
        }
        return response()->json('Project not found.', 404);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $project_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id)
    {
        $project = auth()->user()->project()->find($project_id);

        if ($project) {
            $project->fill($request->all());
            $project->update();
            return response()->json('project changed.');
        }
        return response()->json('Project not found.', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $project_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id)
    {
        $project = auth()->user()->project()->find($project_id);

        if ($project) {
            $project->remove();
            return response()->json('project deleted.');
        }
        return response()->json('Project not found.', 404);
    }

    public function indexWithModels(ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->all();
        return response()->json($projects);
    }

    public function showWithModels($project_id)
    {
        $project = auth()->user()->project()->find($project_id);
        $tasks = $project->tasks;
        unset($project['tasks']);
        return response()->json([
            'project' => $project,
            'tasks' => $tasks
        ]);
    }
}
