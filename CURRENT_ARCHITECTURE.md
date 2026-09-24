# Current Architecture

Krayin CRM is an open-source Laravel-based Customer Relationship Management platform built on Laravel 12, PHP 8.3+, and Pest 3. Its core framework employs a modular package architecture located under `packages/Webkul/`. 

The principal architectural pillars of Krayin CRM include:
- **Package-Based Modularity**: Core modules (`Admin`, `Lead`, `Product`, `Warehouse`, `Contact`, `Activity`, `Quote`, `User`, `Attribute`, `Core`, `DataGrid`, `WebForm`) are registered as independent packages with their own Service Providers, Models, Contracts, Repositories, Routes, Migrations, and Config files.
- **Service Container & Concord Proxies**: Modules leverage `konekt/concord` to bind Eloquent models to contracts and expose proxy classes (e.g. `ProjectProxy`), allowing clean model replacement, extension, and dependency injection across packages without modifying core code.
- **Repository Pattern**: Business logic and database queries are abstracted through repositories inheriting from `Webkul\Core\Eloquent\Repository` (powered by `prettus/l5-repository`), keeping controllers thin and uniform.
- **Dynamic DataGrids**: Tabular data presentation, real-time AJAX pagination, search across columns, multifaceted filtering (dropdowns, date ranges), and row-level or mass actions are managed via `Webkul\DataGrid\DataGrid` abstractions.
- **Native Admin UI & Blade Layouts**: Views consume native reusable Blade components (e.g., `<x-admin::layouts>`, `<x-admin::form>`, `<x-admin::breadcrumbs>`, `<x-admin::datagrid>`) paired with TailwindCSS and custom icon fonts (`app.css`), preserving UI/UX cohesion throughout the system.
- **Unified Menu and ACL Registration**: Modules merge their `Config/menu.php` and `Config/acl.php` into `menu.admin` and `acl` during boot, integrating directly into Krayin's permission tree and left-hand navigation sidebar.

---

# Real Estate Project Management Architecture

The Real Estate Project Management module is developed as a dedicated vertical extension under `packages/Webkul/RealEstate/`. It introduces the foundation for property management by establishing the `Project` entity as the root parent for subsequent inventory and unit management.

### Architectural Highlights
1. **Vertical Isolation**: Generic CRM functionality remains untouched. Real Estate features live entirely inside `packages/Webkul/RealEstate/`, ensuring upgrade-safety and backward compatibility.
2. **Krayin Convention Parity**:
   - Implements `Webkul\RealEstate\Contracts\Project` and `Webkul\RealEstate\Models\ProjectProxy`.
   - Utilizes `Webkul\RealEstate\Repositories\ProjectRepository` extending `Webkul\Core\Eloquent\Repository`.
   - Merges sidebar navigation into `menu.admin` using `icon-settings-warehouse` at sort order `8`.
   - Merges granular ACL nodes into `acl` under the `real_estate` permission key.
   - Provides full translation support with locale parity across `en`, `ar`, `es`, `fa`, `ja`, `ko`, `pt_BR`, `tr`, `vi`, and `zh_CN`.

---

# Database / Models

### Project Model (`Webkul\RealEstate\Models\Project`)
- **Class**: `Webkul\RealEstate\Models\Project`
- **Contract**: `Webkul\RealEstate\Contracts\Project`
- **Proxy**: `Webkul\RealEstate\Models\ProjectProxy`
- **Table**: `real_estate_projects`

#### Database Schema (`real_estate_projects`)
| Column | Type | Modifiers / Notes |
|---|---|---|
| `id` | `increments` (INT UNSIGNED) | Primary Key |
| `project_name` | `string` | Required |
| `project_code` | `string` | Required, Unique, Indexed |
| `developer_name` | `string` | Required |
| `project_type` | `string` | Required (`apartment`, `villa`, `plot`, `commercial`) |
| `description` | `text` | Nullable |
| `address` | `text` | Required |
| `city` | `string` | Required |
| `state` | `string` | Required |
| `pincode` | `string` | Nullable |
| `rera_number` | `string` | Nullable |
| `total_land_area` | `string` | Nullable (e.g., '10.5 Acres') |
| `total_buildings_towers` | `unsignedInteger` | Nullable |
| `expected_completion_date` | `date` | Nullable |
| `status` | `string` | Required, Default `'upcoming'` (`upcoming`, `active`, `on_hold`, `completed`) |
| `created_at` | `timestamp` | Nullable |
| `updated_at` | `timestamp` | Nullable |

### Relationships & Future Property Integration
- **Relationship Method**: `Project::properties()`
  ```php
  public function properties()
  {
      $propertyModel = 'Webkul\RealEstate\Models\PropertyProxy';

      if (class_exists($propertyModel)) {
          return $this->hasMany($propertyModel::modelClass());
      }

      return null;
  }
  ```
- **Future Property Architecture**:
  ```text
  Project (real_estate_projects)
    └── hasMany
          ↓
       Property (real_estate_properties) [Future Module]
  ```
  Every unit/property will contain a `project_id` foreign key referencing `real_estate_projects.id`.
- **Dynamic Inventory Summary Accessors**:
  - `getTotalPropertiesAttribute()`: Returns property count (or 0).
  - `getAvailablePropertiesAttribute()`: Returns available property count (or 0).
  - `getHoldPropertiesAttribute()`: Returns on-hold property count (or 0).
  - `getSoldPropertiesAttribute()`: Returns sold property count (or 0).
  - `getInventorySummaryAttribute()`: Returns keyed summary array: `['total', 'available', 'on_hold', 'sold']`.

---

# Routes / Endpoints

Registered in `packages/Webkul/RealEstate/src/Routes/admin.php` under the `['web', 'admin_locale', 'user']` middlewares:

| Method | URI | Route Name | Action | Description |
|---|---|---|---|---|
| `GET` | `/admin/real-estate/projects` | `admin.real_estate.projects.index` | `ProjectController@index` | Project datagrid & list page |
| `GET` | `/admin/real-estate/projects/create` | `admin.real_estate.projects.create` | `ProjectController@create` | Project creation form |
| `POST` | `/admin/real-estate/projects/create` | `admin.real_estate.projects.store` | `ProjectController@store` | Store new project |
| `GET` | `/admin/real-estate/projects/view/{id}` | `admin.real_estate.projects.view` | `ProjectController@view` | Project details & inventory overview |
| `GET` | `/admin/real-estate/projects/edit/{id}` | `admin.real_estate.projects.edit` | `ProjectController@edit` | Edit project form |
| `PUT` | `/admin/real-estate/projects/edit/{id}` | `admin.real_estate.projects.update` | `ProjectController@update` | Update existing project |
| `DELETE` | `/admin/real-estate/projects/delete/{id}` | `admin.real_estate.projects.delete` | `ProjectController@destroy` | Delete project record |
| `PUT` | `/admin/real-estate/projects/{id}/status` | `admin.real_estate.projects.update_status` | `ProjectController@updateStatus` | Quick status change |
| `GET` | `/admin/real-estate/projects/{id}/properties` | `admin.real_estate.projects.properties` | `ProjectController@properties` | Future Property module placeholder |

---

# Controllers / Services / Repositories

### Key Files Created:
1. **`Webkul\RealEstate\Providers\RealEstateServiceProvider`**
   - **Path**: [`packages/Webkul/RealEstate/src/Providers/RealEstateServiceProvider.php`](file:///c:/Users/Abhi%20Vignesh%20Samala/OneDrive/Desktop/laravel-crm/packages/Webkul/RealEstate/src/Providers/RealEstateServiceProvider.php)
   - **Purpose**: Registers and boots routes, views, translations, migrations, breadcrumbs, menu, and ACL.
2. **`Webkul\RealEstate\Providers\ModuleServiceProvider`**
   - **Path**: [`packages/Webkul/RealEstate/src/Providers/ModuleServiceProvider.php`](file:///c:/Users/Abhi%20Vignesh%20Samala/OneDrive/Desktop/laravel-crm/packages/Webkul/RealEstate/src/Providers/ModuleServiceProvider.php)
   - **Purpose**: Binds Concord proxy models (`Project::class`).
3. **`Webkul\RealEstate\Http\Controllers\ProjectController`**
   - **Path**: [`packages/Webkul/RealEstate/src/Http/Controllers/ProjectController.php`](file:///c:/Users/Abhi%20Vignesh%20Samala/OneDrive/Desktop/laravel-crm/packages/Webkul/RealEstate/src/Http/Controllers/ProjectController.php)
   - **Purpose**: Handles all HTTP requests, CRUD operations, view orchestration, status changes, and AJAX response packaging.
4. **`Webkul\RealEstate\Repositories\ProjectRepository`**
   - **Path**: [`packages/Webkul/RealEstate/src/Repositories/ProjectRepository.php`](file:///c:/Users/Abhi%20Vignesh%20Samala/OneDrive/Desktop/laravel-crm/packages/Webkul/RealEstate/src/Repositories/ProjectRepository.php)
   - **Purpose**: Extends `Webkul\Core\Eloquent\Repository`, defines searchable fields, and encapsulates database querying and status updates.
5. **`Webkul\RealEstate\DataGrids\ProjectDataGrid`**
   - **Path**: [`packages/Webkul/RealEstate/src/DataGrids/ProjectDataGrid.php`](file:///c:/Users/Abhi%20Vignesh%20Samala/OneDrive/Desktop/laravel-crm/packages/Webkul/RealEstate/src/DataGrids/ProjectDataGrid.php)
   - **Purpose**: Prepares query builder, defines columns, search scopes, type/status dropdown filter options, status badges, and action routes (View, Edit, Delete).
6. **`Webkul\RealEstate\Http\Requests\ProjectRequest`**
   - **Path**: [`packages/Webkul/RealEstate/src/Http/Requests/ProjectRequest.php`](file:///c:/Users/Abhi%20Vignesh%20Samala/OneDrive/Desktop/laravel-crm/packages/Webkul/RealEstate/src/Http/Requests/ProjectRequest.php)
   - **Purpose**: Encapsulates validation logic, unique constraints (ignoring current ID on update), and attribute names.

### Existing Files Modified:
1. **`composer.json`**: Added `"Webkul\\RealEstate\\": "packages/Webkul/RealEstate/src"` to `autoload.psr-4`.
2. **`bootstrap/providers.php`**: Registered `Webkul\RealEstate\Providers\RealEstateServiceProvider::class`.
3. **`config/concord.php`**: Registered `Webkul\RealEstate\Providers\ModuleServiceProvider::class`.
4. **`config/database.php`**: Updated MySQL SSL CA option to be PHP 8.5+ compatible.
5. **`CHANGELOG.md`**: Added feature entry under the active version heading.

---

# Frontend / UI

All views are built using native Krayin CRM components and Tailwind styles:
- **Project List (`projects/index.blade.php`)**:
  - Sticky header with breadcrumbs and "Create Project" button guarded by permission checks.
  - Interactive `<x-admin::datagrid>` supporting column-based sorting, pagination, and real-time filtering.
  - Status badges with distinct color palettes: Emerald (Active), Blue (Completed), Amber (On Hold), Purple (Upcoming).
- **Create Project (`projects/create.blade.php`)**:
  - Sticky header with action buttons and breadcrumbs.
  - Two-column responsive layout:
    - **Left column**: Basic Information (Name, Code, Developer, Type, Description) and Location Information (Address, City, State, Pincode).
    - **Right column**: Regulatory & Project Details (Status, RERA Number, Total Land Area, Towers/Buildings, Expected Completion Date).
  - Built using `<x-admin::form.control-group>` with inline VeeValidate and server-side error bindings.
- **Edit Project (`projects/edit.blade.php`)**:
  - Matches the create form structure with automatic pre-population of all existing project attributes.
- **Project Details (`projects/view.blade.php`)**:
  - **Header**: Project name, monospace project code badge, status pill, and actions (Quick status change dropdown, Edit button, Delete button, and disabled "Add Property" button).
  - **Inventory Summary Cards**: 4 responsive metric tiles for Total, Available, On Hold, and Sold units.
  - **Tab Navigation**: Overview (active), Properties, Inventory, Documents, Activity, Settings (cleanly tagged with "Future" pills).
  - **Specification Cards**: Categorized inspection panels for Basic Info, Location Info, Regulatory & Timeline, and Land/Building Specifications.

---

# Authentication / Permissions

Authentication and Authorization use Krayin CRM's native systems (`bouncer()` and `auth('user')`):
- All routes reside behind the `user` middleware.
- Actions and views check permissions defined in `Config/acl.php`:
  - `real_estate`: Root permission group.
  - `real_estate.projects`: Access to project listing and search.
  - `real_estate.projects.view`: View project details.
  - `real_estate.projects.create`: Create new projects.
  - `real_estate.projects.edit`: Update projects and change project status.
  - `real_estate.projects.delete`: Delete projects.

---

# Validation Rules

Form requests enforce the following rules in `ProjectRequest`:

| Field | Rules |
|---|---|
| `project_name` | `required`, `string`, `max:255` |
| `project_code` | `required`, `string`, `max:100`, `unique:real_estate_projects,project_code,{id}` |
| `developer_name` | `required`, `string`, `max:255` |
| `project_type` | `required`, `string`, `in:apartment,villa,plot,commercial` |
| `description` | `nullable`, `string` |
| `address` | `required`, `string` |
| `city` | `required`, `string`, `max:255` |
| `state` | `required`, `string`, `max:255` |
| `pincode` | `nullable`, `string`, `max:20` |
| `rera_number` | `nullable`, `string`, `max:100` |
| `total_land_area` | `nullable`, `string`, `max:100` |
| `total_buildings_towers` | `nullable`, `integer`, `min:0` |
| `expected_completion_date` | `nullable`, `date` |
| `status` | `required`, `string`, `in:upcoming,active,on_hold,completed` |

---

# Project Status Logic

Projects support four lifecycle statuses:
1. **Upcoming** (`upcoming`): Planning, pre-launch, or awaiting regulatory approvals. Default for new projects.
2. **Active** (`active`): Actively under construction and open for property bookings.
3. **On Hold** (`on_hold`): Temporarily paused due to approvals, financing, or site constraints.
4. **Completed** (`completed`): Construction finished and occupancy certificate issued.

Status transitions are permitted from both the Project Details page (via inline status selector) and the Edit Project form, validated against `Project::PROJECT_STATUSES`.

---

# Implemented So Far

- [x] Full module structure under `packages/Webkul/RealEstate/`.
- [x] Database migration for `real_estate_projects` with all required constraints and indexes.
- [x] Eloquent Model `Project` with proxy, contract, status constants, and property relationship placeholders.
- [x] Repository `ProjectRepository` with search and status helper methods.
- [x] Form Request `ProjectRequest` with validation and unique code handling.
- [x] DataGrid `ProjectDataGrid` with search, type/status dropdown filters, date-range filtering, and status badges.
- [x] Controller `ProjectController` with complete CRUD, view, status update, and property placeholder actions.
- [x] Route definitions in `admin.php` and breadcrumb trails in `breadcrumbs.php`.
- [x] Sidebar menu integration (`Config/menu.php`) with valid Krayin icon `icon-settings-warehouse`.
- [x] ACL permissions registered in `Config/acl.php`.
- [x] Complete Blade views: `index.blade.php`, `create.blade.php`, `edit.blade.php`, and `view.blade.php`.
- [x] Complete translations for English plus locale parity across all supported Krayin languages.
- [x] Pest test suite (`tests/Feature/RealEstateProjectTest.php`) with 8 passing feature tests (47 assertions).
- [x] Code formatting validated via Laravel Pint (PSR-12).

---

# Next Implementation

The next developer should continue by implementing:
1. **Property Management Module (`Property`)**:
   - Create table `real_estate_properties` with foreign key `project_id` referencing `real_estate_projects(id)`.
   - Implement fields: `property_number`/`unit_code`, `property_type` (1BHK, 2BHK, 3BHK, Villa, Plot, Commercial Office), `floor_number`, `carpet_area`, `super_built_up_area`, `price`, `status` (`available`, `on_hold`, `sold`), and `project_id`.
   - Connect `Project::properties()` relationship.
2. **Dynamic Inventory Calculations**:
   - Update `Project` accessors (`total_properties`, `available_properties`, `hold_properties`, `sold_properties`) to execute aggregate queries against `real_estate_properties`.
3. **Property Tab in Project Details**:
   - Activate the "Properties" and "Inventory" tabs in `view.blade.php` to display an embedded Property DataGrid scoped to `project_id`.
