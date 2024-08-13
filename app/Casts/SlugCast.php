<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SlugCast implements CastsAttributes
{
    protected array $config;

    public function __construct($model)
    {
        $this->config = $model::slugCastConfig();
    }

    /**
     * Prepare the given value for storage.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if ($value !== "") {
            return $value;
        }

        $this->updateConfig($model, $key);
        $column = $this->config['primary_column'];

        return $this->getSlug($model->$column);
    }

    private function updateConfig(Model $model, string $key): void
    {
        $this->config['table'] = $model->getTable();
        $this->config['primary_column'] ??= $this->getDefaultPrimaryColumn($model);
        $this->config['slug_column'] = $key;
    }

    private function getDefaultPrimaryColumn(Model $model): string
    {
        foreach (['title', 'email', 'name'] as $item) {
            if ($model->hasAttribute($item)) {
                return $item;
            }
        }

        return '';
    }

    /**
     * Make slug unique in db to specific length without cutting the word
     */
    public function getSlug(string $value): string
    {
        if ($this->config['slug_column'] === 'username') {
            $slug = $original_slug = explode('@', $value)[0];
        } else {
            $slug = $original_slug = $this->makeSlug($value);
        }

        do {
            $is_found = DB::table($this->config['table'])
                ->where($this->config['slug_column'], $slug)
                ->first();

            if ($is_found) {
                $slug = "$original_slug-".Str::random($this->config['additional_char']);
            }
        } while ($is_found);

        return $slug;
    }

    /**
     * Make slug to specific length without cutting the word
     */
    private function makeSlug(string $value): string
    {
        $slug = Str::slug($value);
        $max = $this->config['max_char'];

        if ($max > 0 && $max < strlen($slug)) {
            $position = Str::position($slug, '-', $max);

            if ($position) {
                $slug = Str::substr($slug, 0, $position);
            }
        }

        return $slug;
    }

    /**
     * Cast the given value.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return $value;
    }
}
