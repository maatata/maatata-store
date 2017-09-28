<?php

namespace App\Yantrana\__Laraware\Core;

/*
 * __Laraware Core Model - 0.5.0 - 14 JUL 2016
 * 
 * Base Model for Laravel applications
 *
 *
 * Dependencies:
 * 
 * Laravel     5.0 +  - http://laravel.com
 * 
 *
 *-------------------------------------------------------- */

use Datetime;
use Exception;
use Request;
use Cache;
use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class CoreModel extends Eloquent
{
    public static function boot()
    {
        parent::boot();

        // Generate UID if required
        static::creating(function ($model) {
               if ($model->isGenerateUID) {
                   $model->{ $model->UIDKey } = __generateUID();
               }

        });

        // clear cache if exist
        static::saved(function ($model) {
            $model->clearCacheItems();
        });

        // clear cache if exist
        static::deleted(function ($model) {
            $model->clearCacheItems();
        });
    }

    /**
     * Datatable Result counts also its max result per request.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $maxDataTableResultCount = 100;

    /**
     * The generate UID or not.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $isGenerateUID = false;

    /**
     * The UID Name.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $UIDKey = '_uid';

    /**
     * Caching Ids related to this model which may need to clear on add/update/delete.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $cacheIds = [];

    /**
     * Match provided array field values with existing -
     * model field values and update model.
     *
     * @param array $inputs - for update existing model field values
     *
     * @return boolean/array
     *----------------------------------------------------------------------- */
    public function modelUpdate(array $inputs)
    {
        $updatedColumns = [];

        foreach ($inputs as $key => $value) {

    /*
    * Match provided array field with existing -
    * model field and also check values.
    *------------------------------------------------------------------------ */

           if (array_key_exists($key, $this->toArray())
                                and $this->{ $key } != $value) {

                // assign value
                $this->{$key} = $value;

               $updatedColumns[$key] = $value;
           }
        }

        if (!empty($updatedColumns)) {
            if ($this->save()) {
                return $updatedColumns;
            }

            return false;
        }

        return false;
    }

    /**
     * Notes:: this function optimized for __DataStore datatable 0.5.x.
     * 
     * This function manage datatable ajax request. Function -
     * take query and manage all things need in datatable.
     *
     * @param array  $dataTablesConfig - for custom field alias
     * @param object $dataTablesConfig - for database table query scope
     *
     * @return eloquent object
     *------------------------------------------------------------------------ */
    public function scopeDataTables($query, array $dataTablesConfig = array())
    {
        $inputData = Request::all();

        $columns = $inputData['columns'];
        $order = isset($inputData['order']) ? $inputData['order'] : null;

        $sortBy = $this->table.'.'.$this->primaryKey;
        $sortOrder = 'asc';

        // if order not set
        if (!empty($order)) {
            $sortBy = $columns[$order[0]['column']]['data'];
            $sortOrder = $order[0]['dir'];
        }

        $recievedLength = $inputData['length'];

        $perPage = ($recievedLength <= 0)
                            ? $this->maxDataTableResultCount : $recievedLength;

        $start = $inputData['start'] / $perPage;

        /*  Field aliases
        --------------------------------------------------------------------- */

        $fieldAlias = [];

        // if dataTablesConfig fieldAlias exist
        if (!empty($dataTablesConfig['fieldAlias'])) {
            foreach ($dataTablesConfig['fieldAlias'] as $key => $value) {
                $fieldAlias[ $key] = $value;

                // set fieldAlias as sortable 
                if ($key == $sortBy) {
                    $sortBy = $value;
                }
            }
        }

        /* DataTable Search
        --------------------------------------------------------------------- */

        $search = $inputData['search'];
        $searchableColumns = isset($dataTablesConfig['searchable'])
                                ? $dataTablesConfig['searchable'] : null;

        $query->shodh($search['value'], $searchableColumns);

        /*
         if search from datatables ends
        --------------------------------------------------------------------- */

        // check to see if maxDataTableResultCount has been set or not
        $applicableResultCount = (isset($this->maxDataTableResultCount)
                                    and is_int($this->maxDataTableResultCount))
                                    ? $this->maxDataTableResultCount : 100;

        // check if we recived per page request from browser
        $applicableResultCount = $recievedLength
                                    ? $perPage : $applicableResultCount;

        // check if per page request from browser is greter 
        // than maxDataTableResultCount
        $applicableResultCount =
                ($applicableResultCount > $this->maxDataTableResultCount)
                    ? $this->maxDataTableResultCount
                    : $applicableResultCount;

        // finally prepare query                            
        return $query->orderBy($sortBy, $sortOrder)
                     ->paginate($applicableResultCount);
    }

    /**
     * Search in the Model.
     *
     * @param array  $searchTerm        - search term
     * @param object $searchableColumns - columns to search
     *
     * @return eloquent object
     *
     * @since  0.2.0 - 30 NOV 2015
     *------------------------------------------------------------------------ */
    public function scopeShodh($query, $searchTerm, $searchableColumns)
    {
        if (!empty($searchTerm)
            and !empty($searchableColumns) and is_array($searchableColumns)) {
            $query->where(function ($whereQuery) use ($searchableColumns, $searchTerm) {

                foreach ($searchableColumns as $serachableFieldName) {
                    $whereQuery->orWhere(
                        $serachableFieldName, 'like', '%'.$searchTerm.'%');
                }
            });
        }

        return $query;
    }

    /**
     * Assign values to inputs & save model.
     *
     * @param array $input     - input array
     * @param array $keyValues - assign constraints
     *
     * @return bool
     *------------------------------------------------------------------------ */
    public function assignInputsAndSave($input, $keyValues)
    {
        foreach ($keyValues as $key => $value) {
            if (is_string($key)) {
                $this->{$key} = $value;
            } else {
                if (isset($input[$value])) {
                    $this->{$value} = $input[$value];
                }
            }
        }

        unset($input, $keyValues);

        return $this->save();
    }

    /**
     * Prepare uid, timestamp etc & insert.
     *
     * @param array       $input        - input array
     * @param bool/string $returnColumn - if you want ids of the inserted records
     * 
     * @return bool/mixed
     *------------------------------------------------------------------------ */
    public function prepareAndInsert($input, $returnColumn = false)
    {
        $timestamp = new Datetime();
        $preparedRecords = [];
        $itemUids = [];

        // check if needs return item & it should be string or boolean
        if ($returnColumn) {
            if (in_array(gettype($returnColumn), ['boolean', 'string']) === false) {
                throw new Exception('prepareAndInsert - Only boolean or string value is accepted.');
            }
        }

        // check input has contain of array items
        if ((is_array($input) === false) or (isset($input[0]) === false or is_array($input[0]) === false)) {
            throw new Exception('prepareAndInsert - Input should contain array items!!');
        }

        foreach ($input as $item) {
            if ($this->isGenerateUID) { // generate uid if required
                $item[$this->UIDKey] = __generateUID();
                $itemUids[] = $item[$this->UIDKey];
            }

            if ($this->timestamps) { // add timestamps if allowed
                $item['created_at'] = $timestamp;
                $item['updated_at'] = $timestamp;
            }

            $preparedRecords[] = $item;
        }

        // insert the items
        $insertResult = $this->insert($preparedRecords);

        // clear cache items if available
        $this->clearCacheItems();

        unset($input, $timestamp, $preparedRecords);

        //check if return item required ids or selected column
        if (($returnColumn) and ($this->isGenerateUID) and ($insertResult === true)) {

            // item to get return
            $itemToPluck = ($returnColumn === true) ? $this->primaryKey : $returnColumn;

            // get items from db using uids
            $result = $this->whereIn($this->UIDKey, $itemUids)->get([
                $itemToPluck,
             ]);

            // check if pluck method is available which should be in Laravel 5.2+
            if (method_exists($result, 'pluck')) {
                return __ifIsset($result, $result->pluck([$itemToPluck]))->toArray();
            }

            // if not use earlier lists method
            return __ifIsset($result, $result->lists([$itemToPluck]))->toArray();
        }

        return $insertResult;
    }

    /**
     * Clear Cache using CacheIds.
     *
     * @return bool
     *------------------------------------------------------------------------ */
    protected function clearCacheItems()
    {
        if (empty($this->cacheIds) === true) {
            return false;
        }

        foreach ($this->cacheIds as $cacheId) {
            Cache::forget($cacheId);
        }

        return true;
    }

    /**
     * Batch Update.
     *
     * @param string $data  - Data to update along with index key passed 
     *                      using 3rd parameter
     * @param string $index - Index key
     *
     * @return string.
     *-------------------------------------------------------- */
    public function batchUpdate(array $data, $index)
    {
        if (empty($data) or empty($index)) {
            throw new Exception('Invalid data or index');
        }

        $tableName = $this->table;

        $recordsUpdated = DB::transaction(function () use ($tableName, $data, $index) {

            $rawQueryString = 'update '.$tableName.' SET ';
            $updateData = [];
            $ids = $when = [];
            $cases = '';

            //generate the WHEN statements from the set array
            foreach ($data as $key => $val) {
                foreach (array_keys($val) as $field) {
                    if ($field != $index) {
                        $when[$field][] = 'WHEN '.$index
                                        .' = "'.$val[$index].'" THEN ? ';

                        $updateData[$field][] = $val[$field];
                        $ids[$field][] = DB::connection()->getPdo()->quote($val[$index]);
                    }
                }
            }

            if (empty($when) or empty($ids) or empty($updateData)) {
                throw new Exception('Invalid data passed');
            }

            //generate the case statements with the keys and values from the when array
            foreach ($when as $k => $v) {
                $cases .= "\n".$k.' = CASE '."\n";

                foreach ($v as $row) {
                    $cases .= $row."\n";
                }
                $cases .= 'ELSE '.$k.' END, ';
            }

            $rawQueryString .= substr($cases, 0, -2)."\n"; //remove the comma of the last case

            $rawQueryString .= ' WHERE '.$index.' IN ( '.implode(',', array_flatten($ids)).' )';

            return DB::update($rawQueryString, array_flatten($updateData));

        });

        // clear cache items if available
        $this->clearCacheItems();

        return $recordsUpdated;
    }

    /**
     * Delete the model from the database & clear cache items.
     *
     * @param array $query - query
     * 
     * @since  0.5.0 - 14 JUL 2015
     *------------------------------------------------------------------------ */
    public function scopeDeleteIt($query)
    {
        $resultQuery = $query->delete();
         // clear cache items if available
        $this->clearCacheItems();

        return $resultQuery;
    }
}
