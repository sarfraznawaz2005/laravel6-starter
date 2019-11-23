<?php

namespace Modules\Core\Traits\Model;

use Illuminate\Database\Eloquent\Builder;

trait ReadOnlyTrait
{
    /**
     * Throws ReadOnlyException on create
     * @param array $attributes
     * @return bool
     */
    public static function create(array $attributes = []): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on forceCreate
     * @param array $attributes
     * @return bool
     */
    public static function forceCreate(array $attributes): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on save
     * @param array $options
     * @return bool
     */
    public function save(array $options = []): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on update
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = []): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on firstOrCreate
     * @param array $attributes
     * @param array $values
     * @return bool
     */
    public static function firstOrCreate(array $attributes, array $values = []): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on firstOrNew
     * @param array $attributes
     * @param array $values
     * @return bool
     */
    public static function firstOrNew(array $attributes, array $values = []): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on updateOrCreate
     * @param array $attributes
     * @param array $values
     * @return bool
     */
    public static function updateOrCreate(array $attributes, array $values = []): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on delete
     * @return bool
     */
    public function delete(): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on destroy
     * @param mixed $ids
     * @return bool
     */
    public static function destroy($ids): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on restore
     * @return bool
     */
    public function restore(): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on forceDelete
     * @return bool
     */
    public function forceDelete(): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on performDeleteOnModel
     * @return bool
     */
    public function performDeleteOnModel(): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on push
     * @return bool
     */
    public function push(): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on finishSave
     * @param array $options
     * @return bool
     */
    public function finishSave(array $options): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on performUpdate
     * @param Builder $query
     * @param array $options
     * @return bool
     */
    public function performUpdate(Builder $query, array $options = []): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on touch
     * @return bool
     */
    public function touch(): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on insert
     * @return bool
     */
    public function insert(): bool
    {
        return false;
    }

    /**
     * Throws ReadOnlyException on truncate
     * @return bool
     */
    public function truncate(): bool
    {
        return false;
    }
}
