<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('real_estate::app.projects.create.title')
    </x-slot>

    <!-- Create Project Form -->
    <x-admin::form
        :action="route('admin.real_estate.projects.store')"
        method="POST"
    >
        <div class="flex flex-col gap-4">
            <!-- Header Sticky Bar -->
            <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex items-center justify-between rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <!-- Breadcrumbs -->
                    <x-admin::breadcrumbs name="real_estate.projects.create" />

                    <div class="text-xl font-bold dark:text-white">
                        @lang('real_estate::app.projects.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    @if (bouncer()->hasPermission('real_estate.projects.create'))
                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('real_estate::app.projects.create.save-btn')
                        </button>
                    @endif
                </div>
            </div>

            <!-- Content Area (2-column layout) -->
            <div class="flex gap-2.5 max-xl:flex-wrap">
                <!-- Left Sub-component -->
                <div class="flex flex-1 flex-col gap-4 max-xl:flex-auto">
                    <!-- Basic Information Card -->
                    <div class="box-shadow rounded-lg border border-gray-300 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('real_estate::app.projects.create.basic-info')
                        </p>

                        <!-- Project Name -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('real_estate::app.projects.project_name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="project_name"
                                name="project_name"
                                rules="required"
                                :value="old('project_name')"
                                :label="trans('real_estate::app.projects.project_name')"
                                :placeholder="trans('real_estate::app.projects.project_name-placeholder')"
                            />

                            <x-admin::form.control-group.error control-name="project_name" />
                        </x-admin::form.control-group>

                        <!-- Project Code -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('real_estate::app.projects.project_code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="project_code"
                                name="project_code"
                                rules="required"
                                :value="old('project_code')"
                                :label="trans('real_estate::app.projects.project_code')"
                                :placeholder="trans('real_estate::app.projects.project_code-placeholder')"
                            />

                            <x-admin::form.control-group.error control-name="project_code" />
                        </x-admin::form.control-group>

                        <!-- Developer Name -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('real_estate::app.projects.developer_name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="developer_name"
                                name="developer_name"
                                rules="required"
                                :value="old('developer_name')"
                                :label="trans('real_estate::app.projects.developer_name')"
                                :placeholder="trans('real_estate::app.projects.developer_name-placeholder')"
                            />

                            <x-admin::form.control-group.error control-name="developer_name" />
                        </x-admin::form.control-group>

                        <!-- Project Type -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('real_estate::app.projects.project_type')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                id="project_type"
                                name="project_type"
                                rules="required"
                                :value="old('project_type', 'apartment')"
                                :label="trans('real_estate::app.projects.project_type')"
                            >
                                <option value="apartment" {{ old('project_type') == 'apartment' ? 'selected' : '' }}>
                                    @lang('real_estate::app.projects.type-apartment')
                                </option>
                                <option value="villa" {{ old('project_type') == 'villa' ? 'selected' : '' }}>
                                    @lang('real_estate::app.projects.type-villa')
                                </option>
                                <option value="plot" {{ old('project_type') == 'plot' ? 'selected' : '' }}>
                                    @lang('real_estate::app.projects.type-plot')
                                </option>
                                <option value="commercial" {{ old('project_type') == 'commercial' ? 'selected' : '' }}>
                                    @lang('real_estate::app.projects.type-commercial')
                                </option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="project_type" />
                        </x-admin::form.control-group>

                        <!-- Description -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                @lang('real_estate::app.projects.description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                id="description"
                                name="description"
                                :value="old('description')"
                                :label="trans('real_estate::app.projects.description')"
                                :placeholder="trans('real_estate::app.projects.description-placeholder')"
                                rows="4"
                            />

                            <x-admin::form.control-group.error control-name="description" />
                        </x-admin::form.control-group>
                    </div>

                    <!-- Location Information Card -->
                    <div class="box-shadow rounded-lg border border-gray-300 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('real_estate::app.projects.create.location-info')
                        </p>

                        <!-- Address -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('real_estate::app.projects.address')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                id="address"
                                name="address"
                                rules="required"
                                :value="old('address')"
                                :label="trans('real_estate::app.projects.address')"
                                :placeholder="trans('real_estate::app.projects.address-placeholder')"
                                rows="3"
                            />

                            <x-admin::form.control-group.error control-name="address" />
                        </x-admin::form.control-group>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <!-- City -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('real_estate::app.projects.city')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="city"
                                    name="city"
                                    rules="required"
                                    :value="old('city')"
                                    :label="trans('real_estate::app.projects.city')"
                                    :placeholder="trans('real_estate::app.projects.city')"
                                />

                                <x-admin::form.control-group.error control-name="city" />
                            </x-admin::form.control-group>

                            <!-- State -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('real_estate::app.projects.state')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="state"
                                    name="state"
                                    rules="required"
                                    :value="old('state')"
                                    :label="trans('real_estate::app.projects.state')"
                                    :placeholder="trans('real_estate::app.projects.state')"
                                />

                                <x-admin::form.control-group.error control-name="state" />
                            </x-admin::form.control-group>

                            <!-- Pincode -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('real_estate::app.projects.pincode')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="pincode"
                                    name="pincode"
                                    :value="old('pincode')"
                                    :label="trans('real_estate::app.projects.pincode')"
                                    :placeholder="trans('real_estate::app.projects.pincode')"
                                />

                                <x-admin::form.control-group.error control-name="pincode" />
                            </x-admin::form.control-group>
                        </div>
                    </div>
                </div>

                <!-- Right Sub-component -->
                <div class="flex w-[380px] max-w-full flex-col gap-4 max-sm:w-full">
                    <!-- Project Details Card -->
                    <div class="box-shadow rounded-lg border border-gray-300 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('real_estate::app.projects.create.project-details')
                        </p>

                        <!-- Status -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('real_estate::app.projects.status')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                id="status"
                                name="status"
                                rules="required"
                                :value="old('status', 'upcoming')"
                                :label="trans('real_estate::app.projects.status')"
                            >
                                <option value="upcoming" {{ old('status', 'upcoming') == 'upcoming' ? 'selected' : '' }}>
                                    @lang('real_estate::app.projects.status-upcoming')
                                </option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                    @lang('real_estate::app.projects.status-active')
                                </option>
                                <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>
                                    @lang('real_estate::app.projects.status-on-hold')
                                </option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                    @lang('real_estate::app.projects.status-completed')
                                </option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="status" />
                        </x-admin::form.control-group>

                        <!-- RERA Number -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('real_estate::app.projects.rera_number')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="rera_number"
                                name="rera_number"
                                :value="old('rera_number')"
                                :label="trans('real_estate::app.projects.rera_number')"
                                :placeholder="trans('real_estate::app.projects.rera_number-placeholder')"
                            />

                            <x-admin::form.control-group.error control-name="rera_number" />
                        </x-admin::form.control-group>

                        <!-- Total Land Area -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('real_estate::app.projects.total_land_area')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="total_land_area"
                                name="total_land_area"
                                :value="old('total_land_area')"
                                :label="trans('real_estate::app.projects.total_land_area')"
                                :placeholder="trans('real_estate::app.projects.total_land_area-placeholder')"
                            />

                            <x-admin::form.control-group.error control-name="total_land_area" />
                        </x-admin::form.control-group>

                        <!-- Total Buildings / Towers -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('real_estate::app.projects.total_buildings_towers')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="number"
                                id="total_buildings_towers"
                                name="total_buildings_towers"
                                :value="old('total_buildings_towers')"
                                :label="trans('real_estate::app.projects.total_buildings_towers')"
                                :placeholder="trans('real_estate::app.projects.total_buildings_towers')"
                                min="0"
                            />

                            <x-admin::form.control-group.error control-name="total_buildings_towers" />
                        </x-admin::form.control-group>

                        <!-- Expected Completion Date -->
                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label>
                                @lang('real_estate::app.projects.expected_completion_date')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="date"
                                id="expected_completion_date"
                                name="expected_completion_date"
                                :value="old('expected_completion_date')"
                                :label="trans('real_estate::app.projects.expected_completion_date')"
                                :placeholder="trans('real_estate::app.projects.expected_completion_date')"
                            />

                            <x-admin::form.control-group.error control-name="expected_completion_date" />
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
