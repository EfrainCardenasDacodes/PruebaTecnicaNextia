<?php

namespace App\Core\Data;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

/**
 * Base repository
 */
abstract class BaseRepository implements Repository
{
    use Paginable;

    /**
     * Get model by ID
     *
     * @param integer $id
     * @return Model|null
     */
    function getByID(int $id, array $relations = null): ?Model
    {
        // Get model
        $model = $this->getModel();

        // Get relationships
        $query = $this->getRelationships($model->newQuery(), $relations);

        // Return the model
        return $query->find($id);
    }

    /**
     * Get model from trash by ID
     *
     * @param integer $id
     * @return Model|null
     */
    function getTrashByID(int $id, array $relations = null): ?Model
    {
        // Get model
        $model = $this->getModel();

        // Get relationships
        $query = $this->getRelationships($model->newQuery(), $relations);

        // Return the first model searched in trash
        return $query->withTrashed()
            ->find($id);
    }

    /**
     * Get model by filters
     *
     * @param array $filters
     * @return Model|null
     */
    function getByFilters(array $filters): ?Model
    {
        // Get model
        $model = $this->getModel();

        // Get query filters
        $query = $this->getQuery($model, $filters);

        // Get first model founded
        return $query->first();
    }

    /**
     * Get all models
     *
     * @return Collection|Paginator
     */
    function getAll(array $filters = null, ?array $relations = null, ?string $orderBy = 'desc')
    {
        // Get model
        $model = $this->getModel();

        // Get query filters
        $query = $this->getQuery($model, $filters);

        // Get relationships
        $query = $this->getRelationships($query, $relations);

        // Search on trashed models
        $query = $query->withTrashed();
        $query = $query->orderBy('created_at', $orderBy);

        // Return data or pagination
        return $this->getPaginationOrData($query);
    }

    /**
     * Get only active models list
     *
     * @return Collection|Paginator
     */
    function getOnlyActives(array $filters = null, ?array $relationships = null, ?string $orderBy = 'desc')
    {
        // Get model
        $model = $this->getModel();

        // Get query filters
        $query = $this->getQuery($model, $filters);

        // Get relationships
        $query = $this->getRelationships($query, $relationships);

        // If timestamps is activated
        if ($model->timestamps) {
            $query = $query->orderBy('created_at', $orderBy);
        }

        // Return pagination or data
        return $this->getPaginationOrData($query);
    }

    /**
     * Create or update a model
     *
     * @param array $identifiers
     * @param array $attributes
     * @return Model
     */
    function createOrUpdate(array $attributes, array $identifiers = null): Model
    {
        // Get model
        $model = $this->getModel();

        // Check if identifiers exists
        if ($identifiers == null) {
            // Create new model
            return $model->create($attributes);
        }

        // Create or update model
        return $model->updateOrCreate($identifiers, $attributes);
    }

    /**
     * Delete a model
     *
     * @param integer $id
     * @return boolean
     */
    function delete(int $id): bool
    {
        // Get model
        $model = $this->getModel();

        // Delete modelo
        return $model->find($id)
            ->delete();
    }

    /**
     * Delete by filters
     *
     * @param array $filters
     * @return boolean
     */
    function deleteBy(array $filters)
    {
        // Get model
        $model = $this->getModel();

        // Get query filters
        $query = $this->getQuery($model, $filters);

        // Delete
        $query->delete();
    }

    /**
     * Restore a model
     *
     * @param integer $id
     * @return boolean
     */
    function restore(int $id): bool
    {
        // Get model
        $model = $this->getModel();

        // Restore model
        return $model->withTrashed()
            ->find($id)
            ->restore();
    }

    /**
     * Get query builder
     *
     * @param Model $model
     * @param array|null $filters
     * @return Builder
     */
    protected function getQuery(Model $model, ?array $filters): Builder
    {
        // Create new query
        $query = $model->newQuery();

        // Check if filters exists
        if ($filters != null) {
            // Get valid filter keys
            $keys = array_intersect($model->filters ?? [], array_keys($filters));

            // Iterate over each valid key
            foreach ($keys as $key) {
                // If key exists
                if ($filters[$key] != null) {
                    if ($model->filtersOperators != null && array_key_exists($key, $model->filtersOperators)) {
                        // Adding condition to query
                        $query->where($key, $model->filtersOperators[$key], $filters[$key]);
                    } else {
                        // Adding condition to query
                        // Check if is an id for uniquenes
                        if(strpos($key, "_id") != false) {
                            $query->where($key,$filters[$key]);
                        } else {
                            $query->where($key, 'like', '%' . $filters[$key] . '%');
                        }
                    }
                }
            }
        }

        // Return query builder
        return $query;
    }

    /**
     * Get pagination or collection
     *
     * @param Builder $query
     * @return Paginator|Collection
     */
    protected function getPaginationOrData(Builder $query)
    {
        // Check if paginations is enabled
        if ($this->withPagination) {
            // Add pagination to query builder
            return $query->paginate($this->itemsPerPage);
        }

        // Return collection
        return $query->get();
    }

    /**
     * Get model relationships
     *
     * @param Model $model
     * @param array|null $relationships
     * @return Builder
     */
    protected function getRelationships(Builder $query, ?array $relationships): Builder
    {
        // Check if relationships exists
        if ($relationships != null && !empty($relationships)) {
            return $query->with($relationships);
        }

        // Otherwise return the model
        return $query;
    }
}
