<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Webkul\RealEstate\Http\Requests\ProjectRequest;
use Webkul\RealEstate\Models\Project;
use Webkul\RealEstate\Repositories\ProjectRepository;

beforeEach(function () {
    config([
        'database.default' => 'sqlite',
        'database.connections.sqlite.database' => ':memory:',
    ]);
    DB::purge();

    // Create table in memory
    Schema::create('real_estate_projects', function ($table) {
        $table->increments('id');
        $table->string('project_name');
        $table->string('project_code')->unique()->index();
        $table->string('developer_name');
        $table->string('project_type');
        $table->text('description')->nullable();
        $table->text('address');
        $table->string('city');
        $table->string('state');
        $table->string('pincode')->nullable();
        $table->string('rera_number')->nullable();
        $table->string('total_land_area')->nullable();
        $table->unsignedInteger('total_buildings_towers')->nullable();
        $table->date('expected_completion_date')->nullable();
        $table->string('status')->default('upcoming');
        $table->timestamps();
    });

    Schema::create('core_config', function ($table) {
        $table->increments('id');
        $table->string('code');
        $table->string('value');
        $table->timestamps();
    });
});

it('can create a project with valid data and verify default inventory summary', function () {
    $repository = app(ProjectRepository::class);

    $data = [
        'project_name' => 'Skyline Towers',
        'project_code' => 'PRJ-SKY-001',
        'developer_name' => 'Apex Developers',
        'project_type' => 'apartment',
        'description' => 'Luxury high-rise residential towers.',
        'address' => '123 MG Road, Sector 4',
        'city' => 'Bangalore',
        'state' => 'Karnataka',
        'pincode' => '560001',
        'rera_number' => 'PRM/KA/RERA/1251/310/PR/2026/001',
        'total_land_area' => '5.5 Acres',
        'total_buildings_towers' => 4,
        'expected_completion_date' => '2028-12-31',
        'status' => 'active',
    ];

    $project = $repository->create($data);

    expect($project)->toBeInstanceOf(Project::class);
    expect($project->project_name)->toBe('Skyline Towers');
    expect($project->project_code)->toBe('PRJ-SKY-001');
    expect($project->status)->toBe('active');
    expect($project->project_type)->toBe('apartment');

    // Test future-ready inventory summary
    $summary = $project->inventory_summary;
    expect($summary)->toBeArray();
    expect($summary['total'])->toBe(0);
    expect($summary['available'])->toBe(0);
    expect($summary['on_hold'])->toBe(0);
    expect($summary['sold'])->toBe(0);
});

it('enforces unique project code on creation', function () {
    $repository = app(ProjectRepository::class);

    $data = [
        'project_name' => 'Green Acres',
        'project_code' => 'PRJ-UNIQUE-101',
        'developer_name' => 'Green Infra',
        'project_type' => 'villa',
        'address' => 'Survey 42',
        'city' => 'Hyderabad',
        'state' => 'Telangana',
        'status' => 'upcoming',
    ];

    $repository->create($data);

    // Attempt duplicate creation
    expect(function () use ($repository, $data) {
        $repository->create($data);
    })->toThrow(QueryException::class);
});

it('can update project details and change status', function () {
    $repository = app(ProjectRepository::class);

    $project = $repository->create([
        'project_name' => 'Palm Springs',
        'project_code' => 'PRJ-PALM-002',
        'developer_name' => 'Coastline Builders',
        'project_type' => 'plot',
        'address' => 'Coastal Highway',
        'city' => 'Chennai',
        'state' => 'Tamil Nadu',
        'status' => 'upcoming',
    ]);

    expect($project->status)->toBe('upcoming');

    // Update status to active
    $updated = $repository->updateStatus($project->id, 'active');
    expect($updated->status)->toBe('active');

    // Update status to completed
    $updated = $repository->updateStatus($project->id, 'completed');
    expect($updated->status)->toBe('completed');

    // Update details
    $repository->update([
        'project_name' => 'Palm Springs Luxury Plots',
    ], $project->id);

    $refreshed = $project->fresh();
    expect($refreshed->project_name)->toBe('Palm Springs Luxury Plots');
});

it('can delete a project', function () {
    $repository = app(ProjectRepository::class);

    $project = $repository->create([
        'project_name' => 'Sunset Heights',
        'project_code' => 'PRJ-SUN-003',
        'developer_name' => 'Sunrise Corp',
        'project_type' => 'commercial',
        'address' => 'Business Bay',
        'city' => 'Mumbai',
        'state' => 'Maharashtra',
        'status' => 'on_hold',
    ]);

    $id = $project->id;
    $repository->delete($id);

    expect(Project::find($id))->toBeNull();
});

it('validates allowed project types and statuses constants', function () {
    expect(Project::PROJECT_TYPES)->toContain('apartment', 'villa', 'plot', 'commercial');
    expect(Project::PROJECT_STATUSES)->toContain('upcoming', 'active', 'on_hold', 'completed');
});

it('validates project request validation rules', function () {
    $request = new ProjectRequest;
    $rules = $request->rules();

    // Required fields
    expect($rules['project_name'])->toBe('required|string|max:255');
    expect($rules['developer_name'])->toBe('required|string|max:255');
    expect($rules['address'])->toBe('required|string');
    expect($rules['city'])->toBe('required|string|max:255');
    expect($rules['state'])->toBe('required|string|max:255');

    // Fails on missing required fields
    $validator = Validator::make([], $rules);
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('project_name'))->toBeTrue();
    expect($validator->errors()->has('project_code'))->toBeTrue();
    expect($validator->errors()->has('developer_name'))->toBeTrue();
    expect($validator->errors()->has('project_type'))->toBeTrue();
    expect($validator->errors()->has('address'))->toBeTrue();
    expect($validator->errors()->has('city'))->toBeTrue();
    expect($validator->errors()->has('state'))->toBeTrue();
    expect($validator->errors()->has('status'))->toBeTrue();
});

it('verifies menu configuration includes project management with valid icon', function () {
    $menuConfig = config('menu.admin');
    $projectMenu = collect($menuConfig)->firstWhere('key', 'projects');

    expect($projectMenu)->not->toBeNull();
    expect($projectMenu['route'])->toBe('admin.real_estate.projects.index');
    expect($projectMenu['icon-class'])->toBe('icon-settings-warehouse');
});

it('verifies acl configuration includes real estate permissions', function () {
    $aclConfig = config('acl');
    $keys = collect($aclConfig)->pluck('key')->all();

    expect($keys)->toContain('real_estate');
    expect($keys)->toContain('real_estate.projects');
    expect($keys)->toContain('real_estate.projects.create');
    expect($keys)->toContain('real_estate.projects.view');
    expect($keys)->toContain('real_estate.projects.edit');
    expect($keys)->toContain('real_estate.projects.delete');
});

it('verifies breadcrumbs are registered for all real estate project routes', function () {
    expect(Breadcrumbs::exists('real_estate.projects'))->toBeTrue();
    expect(Breadcrumbs::exists('real_estate.projects.create'))->toBeTrue();
    expect(Breadcrumbs::exists('real_estate.projects.view'))->toBeTrue();
    expect(Breadcrumbs::exists('real_estate.projects.edit'))->toBeTrue();

    $indexCrumbs = Breadcrumbs::generate('real_estate.projects');
    expect($indexCrumbs->count())->toBe(2);
    expect($indexCrumbs->last()->title)->toBe(trans('real_estate::app.projects.index.title'));

    $createCrumbs = Breadcrumbs::generate('real_estate.projects.create');
    expect($createCrumbs->count())->toBe(3);
    expect($createCrumbs->last()->title)->toBe(trans('real_estate::app.projects.create.title'));

    $mockProject = (object) ['id' => 10, 'project_name' => 'Grand Horizon'];
    $viewCrumbs = Breadcrumbs::generate('real_estate.projects.view', $mockProject);
    expect($viewCrumbs->count())->toBe(3);
    expect($viewCrumbs->last()->title)->toBe('Grand Horizon');

    $editCrumbs = Breadcrumbs::generate('real_estate.projects.edit', $mockProject);
    expect($editCrumbs->count())->toBe(3);
    expect($editCrumbs->last()->title)->toBe(trans('real_estate::app.projects.edit.title'));
});
