<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    /**
     * Format age and load data into associative array with age_range as key and loads as value.
     * @return [] Ie. ['18-30' => 0.6...]
     */
    public function formatAgeLoadData()
    {
        $age_range = $this->attributes['age_range'];
        $loads = $this->attributes['load'];

        if ($age_range && $loads) {
            $age_range = explode(',', $age_range);
            $loads = explode(',', $loads);

            $age_loads = array_combine($age_range, $loads);

            return $age_loads;
        }

        return [];
    }
}
