<?php

return [
    'menu' => [
        'projects' => 'Project Management',
    ],

    'acl' => [
        'real_estate' => 'Real Estate',
        'projects' => 'Projects',
        'create' => 'Create',
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],

    'projects' => [
        'title' => 'Projects',
        'project_name' => 'Project Name',
        'project_name-placeholder' => 'Enter project name',
        'project_code' => 'Project Code',
        'project_code-placeholder' => 'Enter unique project code (e.g., PRJ-001)',
        'developer_name' => 'Developer Name',
        'developer_name-placeholder' => 'Enter developer or builder name',
        'project_type' => 'Project Type',
        'description' => 'Description',
        'description-placeholder' => 'Enter project description, highlights and notes',
        'address' => 'Address',
        'address-placeholder' => 'Enter site or project address',
        'city' => 'City',
        'state' => 'State',
        'pincode' => 'Pincode',
        'rera_number' => 'RERA Number',
        'rera_number-placeholder' => 'e.g., RERA/P/2026/00123',
        'total_land_area' => 'Total Land Area',
        'total_land_area-placeholder' => 'e.g., 10.5 Acres or 50,000 sq.ft.',
        'total_buildings_towers' => 'Total Buildings / Towers',
        'expected_completion_date' => 'Expected Completion Date',
        'status' => 'Status',

        // Types
        'type-apartment' => 'Apartment',
        'type-villa' => 'Villa',
        'type-plot' => 'Plot',
        'type-commercial' => 'Commercial',

        // Statuses
        'status-upcoming' => 'Upcoming',
        'status-active' => 'Active',
        'status-on-hold' => 'On Hold',
        'status-completed' => 'Completed',

        // Flash & Response messages
        'create-success' => 'Project created successfully.',
        'update-success' => 'Project updated successfully.',
        'delete-success' => 'Project deleted successfully.',
        'delete-failed' => 'Failed to delete project.',
        'status-update-success' => 'Project status updated successfully.',
        'properties-future-notice' => 'Property & Inventory Management features will be available in future releases.',

        'index' => [
            'title' => 'Project Management',
            'create-btn' => 'Create Project',
            'datagrid' => [
                'id' => 'ID',
                'project_name' => 'Project Name',
                'project_code' => 'Project Code',
                'developer_name' => 'Developer',
                'project_type' => 'Project Type',
                'city' => 'City',
                'rera_number' => 'RERA Number',
                'status' => 'Status',
                'created_at' => 'Created At',
                'view' => 'View',
                'edit' => 'Edit',
                'delete' => 'Delete',
            ],
        ],

        'create' => [
            'title' => 'Create Project',
            'save-btn' => 'Save Project',
            'basic-info' => 'Basic Information',
            'location-info' => 'Location Information',
            'project-details' => 'Regulatory & Project Details',
        ],

        'edit' => [
            'title' => 'Edit Project',
            'save-btn' => 'Save Project',
        ],

        'view' => [
            'title' => 'Project Details',
            'edit-btn' => 'Edit Project',
            'delete-btn' => 'Delete',
            'delete-confirm' => 'Are you sure you want to delete this project?',
            'add-property-btn' => 'Add Property',
            'view-properties-btn' => 'View Properties',
            'no-description' => 'No description provided.',
            'regulatory-info' => 'Regulatory & Timeline',
            'specifications' => 'Land & Structure Specifications',
            'created-at' => 'Created At',
            'updated-at' => 'Last Updated',
            'inventory' => [
                'total' => 'Total Properties',
                'available' => 'Available Properties',
                'on-hold' => 'Properties on Hold',
                'sold' => 'Sold Properties',
            ],
            'tabs' => [
                'overview' => 'Overview',
                'properties' => 'Properties',
                'inventory' => 'Inventory',
                'documents' => 'Documents',
                'activity' => 'Activity',
                'settings' => 'Settings',
                'future' => 'Future',
            ],
        ],
    ],
];
