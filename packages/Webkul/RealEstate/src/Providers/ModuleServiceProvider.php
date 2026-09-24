<?php

namespace Webkul\RealEstate\Providers;

use Webkul\Core\Providers\BaseModuleServiceProvider;
use Webkul\RealEstate\Models\Project;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Project::class,
    ];
}
