<x-admin::layouts>
    <x-slot:title>
        @lang('real_estate::app.projects.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex items-center justify-between rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <!-- Breadcrumbs -->
                <x-admin::breadcrumbs name="real_estate.projects" />

                <div class="text-xl font-bold dark:text-white">
                    @lang('real_estate::app.projects.index.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('real_estate.projects.create'))
                    <a
                        href="{{ route('admin.real_estate.projects.create') }}"
                        class="primary-button"
                    >
                        @lang('real_estate::app.projects.index.create-btn')
                    </a>
                @endif
            </div>
        </div>

        <x-admin::datagrid :src="route('admin.real_estate.projects.index')">
            <!-- DataGrid Shimmer -->
            <x-admin::shimmer.datagrid />
        </x-admin::datagrid>
    </div>
</x-admin::layouts>
