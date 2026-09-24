<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $project->project_name }} - @lang('real_estate::app.projects.view.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Header Sticky Action Bar -->
        <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex items-center justify-between rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 max-md:flex-col max-md:items-start max-md:gap-3">
            <div class="flex flex-col gap-1.5">
                <!-- Breadcrumbs -->
                <x-admin::breadcrumbs
                    name="real_estate.projects.view"
                    :entity="$project"
                />

                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        {{ $project->project_name }}
                    </h1>

                    <span class="rounded bg-gray-100 px-2.5 py-0.5 font-mono text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        {{ $project->project_code }}
                    </span>

                    <!-- Status Badge -->
                    @php
                        $badgeClasses = match($project->status) {
                            'active'    => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                            'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                            'on_hold'   => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                            'upcoming'  => 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                            default     => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                        };
                    @endphp
                    <span class="rounded-full border px-3 py-1 text-xs font-semibold capitalize {{ $badgeClasses }}">
                        @lang('real_estate::app.projects.status-' . str_replace('_', '-', $project->status))
                    </span>
                </div>
            </div>

            <!-- Actions Header -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Quick Status Change Form -->
                @if (bouncer()->hasPermission('real_estate.projects.edit'))
                    <form
                        action="{{ route('admin.real_estate.projects.update_status', $project->id) }}"
                        method="POST"
                        class="flex items-center gap-1"
                    >
                        @csrf
                        @method('PUT')
                        <select
                            name="status"
                            onchange="this.form.submit()"
                            class="custom-select rounded border border-gray-300 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-700 shadow-sm transition hover:border-gray-400 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            <option value="upcoming" {{ $project->status === 'upcoming' ? 'selected' : '' }}>
                                @lang('real_estate::app.projects.status-upcoming')
                            </option>
                            <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>
                                @lang('real_estate::app.projects.status-active')
                            </option>
                            <option value="on_hold" {{ $project->status === 'on_hold' ? 'selected' : '' }}>
                                @lang('real_estate::app.projects.status-on-hold')
                            </option>
                            <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>
                                @lang('real_estate::app.projects.status-completed')
                            </option>
                        </select>
                    </form>
                @endif

                <!-- Edit Project Button -->
                @if (bouncer()->hasPermission('real_estate.projects.edit'))
                    <a
                        href="{{ route('admin.real_estate.projects.edit', $project->id) }}"
                        class="secondary-button"
                    >
                        <i class="icon-edit text-base"></i>
                        @lang('real_estate::app.projects.view.edit-btn')
                    </a>
                @endif

                <!-- Future Placeholder: Add Property -->
                <button
                    type="button"
                    class="secondary-button cursor-not-allowed opacity-60"
                    title="@lang('real_estate::app.projects.properties-future-notice')"
                    disabled
                >
                    <i class="icon-add text-base"></i>
                    @lang('real_estate::app.projects.view.add-property-btn')
                </button>

                <!-- Delete Project Button -->
                @if (bouncer()->hasPermission('real_estate.projects.delete'))
                    <form
                        action="{{ route('admin.real_estate.projects.delete', $project->id) }}"
                        method="POST"
                        onsubmit="return confirm('@lang('real_estate::app.projects.view.delete-confirm')')"
                    >
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="rounded border border-red-300 bg-white px-3 py-1.5 text-sm font-medium text-red-600 shadow-sm transition hover:bg-red-50 dark:border-red-800 dark:bg-gray-900 dark:text-red-400 dark:hover:bg-red-950/30"
                        >
                            <i class="icon-delete text-base"></i>
                            @lang('real_estate::app.projects.view.delete-btn')
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Inventory Summary Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Properties -->
            <div class="box-shadow flex items-center justify-between rounded-lg border border-gray-300 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        @lang('real_estate::app.projects.view.inventory.total')
                    </span>
                    <span class="text-2xl font-bold text-gray-800 dark:text-white">
                        {{ $project->inventory_summary['total'] }}
                    </span>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-400">
                    <i class="icon-settings-warehouse text-2xl"></i>
                </div>
            </div>

            <!-- Available Properties -->
            <div class="box-shadow flex items-center justify-between rounded-lg border border-gray-300 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        @lang('real_estate::app.projects.view.inventory.available')
                    </span>
                    <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                        {{ $project->inventory_summary['available'] }}
                    </span>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                    <i class="icon-checkbox-select text-2xl"></i>
                </div>
            </div>

            <!-- On Hold Properties -->
            <div class="box-shadow flex items-center justify-between rounded-lg border border-gray-300 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        @lang('real_estate::app.projects.view.inventory.on-hold')
                    </span>
                    <span class="text-2xl font-bold text-amber-600 dark:text-amber-400">
                        {{ $project->inventory_summary['on_hold'] }}
                    </span>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400">
                    <i class="icon-warning text-2xl"></i>
                </div>
            </div>

            <!-- Sold Properties -->
            <div class="box-shadow flex items-center justify-between rounded-lg border border-gray-300 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        @lang('real_estate::app.projects.view.inventory.sold')
                    </span>
                    <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $project->inventory_summary['sold'] }}
                    </span>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 text-purple-600 dark:bg-purple-950 dark:text-purple-400">
                    <i class="icon-activity text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-800">
            <nav class="-mb-px flex space-x-6 overflow-x-auto text-sm font-medium">
                <!-- Overview Tab (Active) -->
                <span class="inline-flex cursor-pointer items-center border-b-2 border-brandColor px-1 pb-3 text-sm font-semibold text-brandColor">
                    @lang('real_estate::app.projects.view.tabs.overview')
                </span>

                <!-- Properties Tab (Future) -->
                <span class="inline-flex cursor-not-allowed items-center gap-1.5 border-b-2 border-transparent px-1 pb-3 text-sm text-gray-400 dark:text-gray-500">
                    @lang('real_estate::app.projects.view.tabs.properties')
                    <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] uppercase font-bold text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        @lang('real_estate::app.projects.view.tabs.future')
                    </span>
                </span>

                <!-- Inventory Tab (Future) -->
                <span class="inline-flex cursor-not-allowed items-center gap-1.5 border-b-2 border-transparent px-1 pb-3 text-sm text-gray-400 dark:text-gray-500">
                    @lang('real_estate::app.projects.view.tabs.inventory')
                    <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] uppercase font-bold text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        @lang('real_estate::app.projects.view.tabs.future')
                    </span>
                </span>

                <!-- Documents Tab (Future) -->
                <span class="inline-flex cursor-not-allowed items-center gap-1.5 border-b-2 border-transparent px-1 pb-3 text-sm text-gray-400 dark:text-gray-500">
                    @lang('real_estate::app.projects.view.tabs.documents')
                    <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] uppercase font-bold text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        @lang('real_estate::app.projects.view.tabs.future')
                    </span>
                </span>

                <!-- Activity Tab (Future) -->
                <span class="inline-flex cursor-not-allowed items-center gap-1.5 border-b-2 border-transparent px-1 pb-3 text-sm text-gray-400 dark:text-gray-500">
                    @lang('real_estate::app.projects.view.tabs.activity')
                    <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] uppercase font-bold text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        @lang('real_estate::app.projects.view.tabs.future')
                    </span>
                </span>

                <!-- Settings Tab (Future) -->
                <span class="inline-flex cursor-not-allowed items-center gap-1.5 border-b-2 border-transparent px-1 pb-3 text-sm text-gray-400 dark:text-gray-500">
                    @lang('real_estate::app.projects.view.tabs.settings')
                    <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] uppercase font-bold text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        @lang('real_estate::app.projects.view.tabs.future')
                    </span>
                </span>
            </nav>
        </div>

        <!-- Tab Content: Overview Section -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <!-- Basic Information Card -->
            <div class="box-shadow rounded-lg border border-gray-300 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <div class="mb-4 flex items-center justify-between border-b pb-3 dark:border-gray-800">
                    <h2 class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('real_estate::app.projects.create.basic-info')
                    </h2>
                </div>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.project_name')</dt>
                        <dd class="font-semibold text-gray-800 dark:text-white">{{ $project->project_name }}</dd>
                    </div>

                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.project_code')</dt>
                        <dd class="font-mono text-gray-800 dark:text-white">{{ $project->project_code }}</dd>
                    </div>

                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.developer_name')</dt>
                        <dd class="text-gray-800 dark:text-white">{{ $project->developer_name }}</dd>
                    </div>

                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.project_type')</dt>
                        <dd class="capitalize text-gray-800 dark:text-white">
                            @lang('real_estate::app.projects.type-' . $project->project_type)
                        </dd>
                    </div>

                    <div class="py-1">
                        <dt class="mb-1 font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.description')</dt>
                        <dd class="rounded-md bg-gray-50 p-3 text-gray-700 dark:bg-gray-800/50 dark:text-gray-300">
                            {{ $project->description ?: trans('real_estate::app.projects.view.no-description') }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Location Information Card -->
            <div class="box-shadow rounded-lg border border-gray-300 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <div class="mb-4 flex items-center justify-between border-b pb-3 dark:border-gray-800">
                    <h2 class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('real_estate::app.projects.create.location-info')
                    </h2>
                </div>

                <dl class="space-y-3 text-sm">
                    <div class="py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="mb-1 font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.address')</dt>
                        <dd class="text-gray-800 dark:text-white">{{ $project->address }}</dd>
                    </div>

                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.city')</dt>
                        <dd class="font-semibold text-gray-800 dark:text-white">{{ $project->city }}</dd>
                    </div>

                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.state')</dt>
                        <dd class="text-gray-800 dark:text-white">{{ $project->state }}</dd>
                    </div>

                    <div class="flex justify-between py-1">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.pincode')</dt>
                        <dd class="font-mono text-gray-800 dark:text-white">{{ $project->pincode ?: '--' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Regulatory & Timeline Information Card -->
            <div class="box-shadow rounded-lg border border-gray-300 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <div class="mb-4 flex items-center justify-between border-b pb-3 dark:border-gray-800">
                    <h2 class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('real_estate::app.projects.view.regulatory-info')
                    </h2>
                </div>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.rera_number')</dt>
                        <dd class="font-mono font-semibold text-gray-800 dark:text-white">{{ $project->rera_number ?: '--' }}</dd>
                    </div>

                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.status')</dt>
                        <dd class="capitalize font-semibold text-gray-800 dark:text-white">
                            @lang('real_estate::app.projects.status-' . str_replace('_', '-', $project->status))
                        </dd>
                    </div>

                    <div class="flex justify-between py-1">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.expected_completion_date')</dt>
                        <dd class="text-gray-800 dark:text-white">
                            {{ $project->expected_completion_date ? core()->formatDate($project->expected_completion_date) : '--' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Land & Structure Specifications Card -->
            <div class="box-shadow rounded-lg border border-gray-300 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <div class="mb-4 flex items-center justify-between border-b pb-3 dark:border-gray-800">
                    <h2 class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('real_estate::app.projects.view.specifications')
                    </h2>
                </div>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.total_land_area')</dt>
                        <dd class="font-semibold text-gray-800 dark:text-white">{{ $project->total_land_area ?: '--' }}</dd>
                    </div>

                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.total_buildings_towers')</dt>
                        <dd class="text-gray-800 dark:text-white">{{ $project->total_buildings_towers !== null ? $project->total_buildings_towers : '--' }}</dd>
                    </div>

                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.view.created-at')</dt>
                        <dd class="text-gray-800 dark:text-white">{{ core()->formatDate($project->created_at, 'd M Y, h:i A') }}</dd>
                    </div>

                    <div class="flex justify-between py-1">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('real_estate::app.projects.view.updated-at')</dt>
                        <dd class="text-gray-800 dark:text-white">{{ core()->formatDate($project->updated_at, 'd M Y, h:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-admin::layouts>
