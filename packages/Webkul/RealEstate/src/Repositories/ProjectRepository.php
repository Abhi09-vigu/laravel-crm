<?php

namespace Webkul\RealEstate\Repositories;

use Webkul\Core\Eloquent\Repository;

class ProjectRepository extends Repository
{
    /**
     * Searchable fields.
     *
     * @var array
     */
    protected $fieldSearchable = [
        'project_name',
        'project_code',
        'developer_name',
        'city',
        'rera_number',
    ];

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\RealEstate\Contracts\Project';
    }

    /**
     * Update project status.
     */
    public function updateStatus(int $id, string $status)
    {
        $project = $this->findOrFail($id);

        $project->update(['status' => $status]);

        return $project;
    }
}
