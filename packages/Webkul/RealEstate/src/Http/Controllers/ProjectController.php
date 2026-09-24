<?php

namespace Webkul\RealEstate\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\RealEstate\DataGrids\ProjectDataGrid;
use Webkul\RealEstate\Http\Requests\ProjectRequest;
use Webkul\RealEstate\Models\Project;
use Webkul\RealEstate\Repositories\ProjectRepository;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected ProjectRepository $projectRepository) {}

    /**
     * Display a listing of projects.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(ProjectDataGrid::class)->process();
        }

        return view('real_estate::projects.index');
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        return view('real_estate::projects.create');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(ProjectRequest $request): RedirectResponse|JsonResponse
    {
        $project = $this->projectRepository->create($request->validated());

        if (request()->ajax()) {
            return response()->json([
                'data' => $project,
                'message' => trans('real_estate::app.projects.create-success'),
                'redirect' => route('admin.real_estate.projects.view', $project->id),
            ]);
        }

        session()->flash('success', trans('real_estate::app.projects.create-success'));

        return redirect()->route('admin.real_estate.projects.view', $project->id);
    }

    /**
     * Show the specified project details.
     */
    public function view(int $id): View
    {
        $project = $this->projectRepository->findOrFail($id);

        return view('real_estate::projects.view', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(int $id): View
    {
        $project = $this->projectRepository->findOrFail($id);

        return view('real_estate::projects.edit', compact('project'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(ProjectRequest $request, int $id): RedirectResponse|JsonResponse
    {
        $project = $this->projectRepository->update($request->validated(), $id);

        if (request()->ajax()) {
            return response()->json([
                'data' => $project,
                'message' => trans('real_estate::app.projects.update-success'),
                'redirect' => route('admin.real_estate.projects.view', $id),
            ]);
        }

        session()->flash('success', trans('real_estate::app.projects.update-success'));

        return redirect()->route('admin.real_estate.projects.view', $id);
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->projectRepository->delete($id);

            return new JsonResponse([
                'message' => trans('real_estate::app.projects.delete-success'),
            ], 200);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'message' => trans('real_estate::app.projects.delete-failed'),
            ], 400);
        }
    }

    /**
     * Update the status of the specified project.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in(Project::PROJECT_STATUSES)],
        ]);

        $project = $this->projectRepository->updateStatus($id, $request->input('status'));

        if (request()->ajax()) {
            return response()->json([
                'data' => $project,
                'message' => trans('real_estate::app.projects.status-update-success'),
            ]);
        }

        session()->flash('success', trans('real_estate::app.projects.status-update-success'));

        return redirect()->back();
    }

    /**
     * Placeholder action for future Property Management.
     */
    public function properties(int $id): View|RedirectResponse
    {
        $project = $this->projectRepository->findOrFail($id);

        session()->flash('warning', trans('real_estate::app.projects.properties-future-notice'));

        return redirect()->route('admin.real_estate.projects.view', $project->id);
    }
}
