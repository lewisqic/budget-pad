<?php

namespace App\Services;

abstract class BaseService
{

    protected $record = null;

    /**
     * Load an existing record
     *
     * @param  array $id
     * @return object
     */
    public function load($id)
    {
        $service = get_called_class();
        $model = str_replace('App\Services\\', '', $service);
        $model = 'App\Models\\' . str_replace('Service', '', $model);
        $this->record = $model::findOrFail($id);
        return $this;
    }

    /**
     * create a record
     *
     * @param  int  $id
     * @return object
     */
    public function create($data)
    {
        $service = get_called_class();
        $model = str_replace('App\Services\\', '', $service);
        $model = 'App\Models\\' . str_replace('Service', '', $model);
        $record = $model::create($data);
        return $record;
    }


    /**
     * update a record
     *
     * @param  array $data
     * @return object
     */
    public function update($data)
    {
        $this->record->fill($data)->save();
        return $this->record;
    }


    /**
     * delete a record
     *
     * @param  int  $id
     * @return object
     */
    public function delete($id)
    {
        $service = get_called_class();
        $model = str_replace('App\Services\\', '', $service);
        $model = 'App\Models\\' . str_replace('Service', '', $model);
        $record = $model::findOrFail($id);
        $record->delete();
        return $record;
    }


    /**
     * restore a record
     *
     * @param  int  $id
     * @return object
     */
    public function restore($id)
    {
        $service = get_called_class();
        $model = str_replace('App\Services\\', '', $service);
        $model = 'App\Models\\' . str_replace('Service', '', $model);
        $record = $model::withTrashed()->findOrFail($id);
        $record->restore();
        return $record;
    }

}
