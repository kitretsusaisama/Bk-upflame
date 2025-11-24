<?php

namespace App\Support\Domain\Shared\Contracts;

interface Repository
{
    /**
     * Find a model by its ID.
     *
     * @param  mixed  $id
     * @return mixed
     */
    public function find($id);

    /**
     * Find a model by its ID or throw an exception.
     *
     * @param  mixed  $id
     * @return mixed
     */
    public function findOrFail($id);

    /**
     * Get all models.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Create a new model.
     *
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update a model.
     *
     * @param  mixed  $id
     * @param  array  $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Delete a model.
     *
     * @param  mixed  $id
     * @return bool
     */
    public function delete($id);
}