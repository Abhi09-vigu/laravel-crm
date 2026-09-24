<?php

namespace Webkul\RealEstate\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RealEstate\Contracts\Project as ProjectContract;

class Project extends Model implements ProjectContract
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'real_estate_projects';

    /**
     * Allowed project types.
     */
    public const TYPE_APARTMENT = 'apartment';

    public const TYPE_VILLA = 'villa';

    public const TYPE_PLOT = 'plot';

    public const TYPE_COMMERCIAL = 'commercial';

    public const PROJECT_TYPES = [
        self::TYPE_APARTMENT,
        self::TYPE_VILLA,
        self::TYPE_PLOT,
        self::TYPE_COMMERCIAL,
    ];

    /**
     * Allowed project statuses.
     */
    public const STATUS_UPCOMING = 'upcoming';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_ON_HOLD = 'on_hold';

    public const STATUS_COMPLETED = 'completed';

    public const PROJECT_STATUSES = [
        self::STATUS_UPCOMING,
        self::STATUS_ACTIVE,
        self::STATUS_ON_HOLD,
        self::STATUS_COMPLETED,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_name',
        'project_code',
        'developer_name',
        'project_type',
        'description',
        'address',
        'city',
        'state',
        'pincode',
        'rera_number',
        'total_land_area',
        'total_buildings_towers',
        'expected_completion_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expected_completion_date' => 'date',
        'total_buildings_towers' => 'integer',
    ];

    /**
     * Future relationship: A project has many properties.
     * Ready for future Property Management module.
     */
    public function properties()
    {
        $propertyModel = 'Webkul\RealEstate\Models\PropertyProxy';

        if (class_exists($propertyModel)) {
            return $this->hasMany($propertyModel::modelClass());
        }

        return null;
    }

    /**
     * Get total properties count.
     */
    public function getTotalPropertiesAttribute(): int
    {
        if ($this->properties()) {
            try {
                return $this->properties()->count();
            } catch (\Throwable $e) {
                return 0;
            }
        }

        return 0;
    }

    /**
     * Get available properties count.
     */
    public function getAvailablePropertiesAttribute(): int
    {
        if ($this->properties()) {
            try {
                return $this->properties()->where('status', 'available')->count();
            } catch (\Throwable $e) {
                return 0;
            }
        }

        return 0;
    }

    /**
     * Get hold properties count.
     */
    public function getHoldPropertiesAttribute(): int
    {
        if ($this->properties()) {
            try {
                return $this->properties()->where('status', 'on_hold')->count();
            } catch (\Throwable $e) {
                return 0;
            }
        }

        return 0;
    }

    /**
     * Get sold properties count.
     */
    public function getSoldPropertiesAttribute(): int
    {
        if ($this->properties()) {
            try {
                return $this->properties()->where('status', 'sold')->count();
            } catch (\Throwable $e) {
                return 0;
            }
        }

        return 0;
    }

    /**
     * Get inventory summary.
     */
    public function getInventorySummaryAttribute(): array
    {
        return [
            'total' => $this->total_properties,
            'available' => $this->available_properties,
            'on_hold' => $this->hold_properties,
            'sold' => $this->sold_properties,
        ];
    }
}
