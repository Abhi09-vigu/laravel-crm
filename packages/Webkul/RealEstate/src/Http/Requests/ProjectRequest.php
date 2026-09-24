<?php

namespace Webkul\RealEstate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webkul\RealEstate\Models\Project;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('id') ?: $this->id;

        $rules = [
            'project_name' => 'required|string|max:255',
            'project_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('real_estate_projects', 'project_code')->ignore($id),
            ],
            'developer_name' => 'required|string|max:255',
            'project_type' => ['required', 'string', Rule::in(Project::PROJECT_TYPES)],
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'nullable|string|max:20',
            'rera_number' => 'nullable|string|max:100',
            'total_land_area' => 'nullable|string|max:100',
            'total_buildings_towers' => 'nullable|integer|min:0',
            'expected_completion_date' => 'nullable|date',
            'status' => ['required', 'string', Rule::in(Project::PROJECT_STATUSES)],
        ];

        return $rules;
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'project_name' => trans('real_estate::app.projects.project_name'),
            'project_code' => trans('real_estate::app.projects.project_code'),
            'developer_name' => trans('real_estate::app.projects.developer_name'),
            'project_type' => trans('real_estate::app.projects.project_type'),
            'description' => trans('real_estate::app.projects.description'),
            'address' => trans('real_estate::app.projects.address'),
            'city' => trans('real_estate::app.projects.city'),
            'state' => trans('real_estate::app.projects.state'),
            'pincode' => trans('real_estate::app.projects.pincode'),
            'rera_number' => trans('real_estate::app.projects.rera_number'),
            'total_land_area' => trans('real_estate::app.projects.total_land_area'),
            'total_buildings_towers' => trans('real_estate::app.projects.total_buildings_towers'),
            'expected_completion_date' => trans('real_estate::app.projects.expected_completion_date'),
            'status' => trans('real_estate::app.projects.status'),
        ];
    }
}
