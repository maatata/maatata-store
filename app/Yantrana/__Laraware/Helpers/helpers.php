<?php

    /**
     * Core Helper - 0.12.9 - 24 JUN 2016.
     * 
     * Common helper functions for Laravel applications
     *
     *
     * Dependencies:
     * 
     * Laravel     5.0 +     - http://laravel.com
     *-------------------------------------------------------- */

    /**
     * Debuging function for debugging javascript side.
     *
     * @param  N numbers of params can be sent 
     *-------------------------------------------------------- */
    if (!function_exists('__dd')) {
        function __dd()
        {
            if (env('APP_DEBUG', false) == false) {
                throw new Exception('Something went wrong!!');
            }

            $args = func_get_args();

            if (empty($args)) {
                throw new Exception('__dd() No arguments are passed!!');
            }

            if (Request::ajax() === false) {
                call_user_func_array('dd', $args);
                exit();
            }

            exit(json_encode([
                '__dd' => '__dd',
                'data' => array_map(function ($argument) {
                    return print_r($argument, true);
                }, $args),
            ]));
        }
    }

    /*
    * Debuging function for debugging javascript as well as PHP side, work as likely print_r but accepts unlimitted parameters
    *
    * @param  N numbers of params can be sent 
    * @return void
    *-------------------------------------------------------- */

    if (!function_exists('__pr')) {
        function __pr()
        {
            if (env('APP_DEBUG', false) == false) {
                return false;
            }

            $args = func_get_args();

            if (empty($args)) {
                throw new Exception('__pr() No arguments are passed!!');
            }

            if (Request::ajax() === false) {
                if (class_exists('\Illuminate\Support\Debug\Dumper')) {
                    return array_map(function ($argument) {
                        (new \Illuminate\Support\Debug\Dumper())->dump($argument, false);
                    }, $args);
                } else {
                    return array_map(function ($argument) {
                        print_r($argument, false);
                    }, $args);
                }
            }

            $prSessItemName = '__pr';

            if (isset($_SESSION[$prSessItemName]) == false) {
                $_SESSION[$prSessItemName] = [];
            }

            return $_SESSION[$prSessItemName][] = array_map(function ($argument) {
                    return print_r($argument, true);
                }, $args);
        }
    }

    /*
    * Debuging function for debugging javascript
    *
    * @param  N numbers of params can be sent 
    * @return void
    *-------------------------------------------------------- */

    if (!function_exists('__clog')) {
        function __clog()
        {
            if (env('APP_DEBUG', false) == false) {
                return false;
            }

            $args = func_get_args();

            if (empty($args)) {
                throw new Exception('__clog() No arguments are passed!!');
            }

            $clogSessItemName = '__clog';

            if (isset($_SESSION[$clogSessItemName]) == false) {
                $_SESSION[$clogSessItemName] = [];
            }

            return $_SESSION[$clogSessItemName][] = array_map(function ($argument) {
                    return print_r($argument, true);
                }, $args);
        }
    }

    /*
    * Utility function to create array of nested array items strings (Concating parent key in to child key) & assign values to it.
    * 
    * @param  $inputArray raw nested array 
    * @param  $requestedJoiner joiner or word for string concat 
    * @param  $prepend prepend string
    * @param  $allStages if you want to create an array item for every stage 
    * 
    * @return void
    *-------------------------------------------------------- */
    if (!function_exists('__nestedKeyValues')) {
        function __nestedKeyValues(array $inputArray, $requestedJoiner = '.', $prepend = null, $allStages = false)
        {
            $formattedArray = [];

            foreach ($inputArray as $key => $value) {
                $joiner = ($prepend == null) ? '' : $requestedJoiner;

                // if array run this again to grab the child items to process
                if (is_array($value)) {
                    if ($allStages === true) {
                        array_push($formattedArray, $prepend);
                    }

                    $formattedArray = array_merge($formattedArray, __nestedKeyValues($value, $requestedJoiner, $prepend.$joiner.$key, $allStages));
                } else {
                    // if key is not string push item in to array with required 
                    if (is_string($key) === false) {
                        if (is_string($value) === true) {
                            array_push($formattedArray, $prepend.$joiner.$value);
                        } else {
                            array_push($formattedArray, $value);
                        }
                    } else {
                        $formattedArray[$prepend.$joiner.$key] = $value;
                    }
                }
            }

            unset($prepend, $joiner, $requestedJoiner, $prepend, $allStages, $inputArray);

            return $formattedArray;
        }
    }
    /*
    * Create JSON object for all HTTP request.
    *
    * @param  array $data 
    * @return JSON Object.
    *-------------------------------------------------------- */

    if (!function_exists('__apiResponse')) {
        function __apiResponse($data, $reactionCode = 1)
        {
            if (isset($data['__useNativeJsonEncode'])
                    and $data['__useNativeJsonEncode'] === true) {
                return json_encode(__response($data, $reactionCode));
            }

            return Response::json(__response($data, $reactionCode));
        }
    }

    /*
    * Echo JSON API response.
    *
    * @param  array $data 
    * @return JSON Object.
    *-------------------------------------------------------- */

    if (!function_exists('__response')) {
        function __response($data, $reactionCode = 1)
        {
            if (Session::has('additional')) {
                $data['additional'] = Session::get('additional');
            }

            $responseData = [
                'data' => $data,
                'response_token' => (int) Request::get('fresh'),
                'reaction' => $reactionCode,
            ];

            if (Session::has('additional')) {
                $responseData['additional'] = Session::get('additional');
            }

            // __pr() to print in console
            if (env('APP_DEBUG', false) == true) {
                $prSessItemName = '__pr';

                if (isset($_SESSION[$prSessItemName]) == true and !empty($_SESSION[$prSessItemName])) {
                    $responseData['__dd'] = true;
                    $responseData[$prSessItemName] = $prSessItemName;
                    // set for response              
                    $responseData[$prSessItemName] = $_SESSION[$prSessItemName];
                    //reset the __pr items in session
                    $_SESSION[$prSessItemName] = [];
                }

                $clogSessItemName = '__clog';

                if (isset($_SESSION[$clogSessItemName]) == true and !empty($_SESSION[$clogSessItemName])) {
                    $responseData['__dd'] = true;
                    $responseData[$clogSessItemName] = $clogSessItemName;
                    // set for response              
                    $responseData[$clogSessItemName] = $_SESSION[$clogSessItemName];
                    //reset the __clog items in session
                    $_SESSION[$clogSessItemName] = [];
                }

                // email view debugging
                if (env('MAIL_VIEW_DEBUG', false) == true) {
                    $testEmailViewSessName = '__emailDebugView';
                    if (isset($_SESSION[$testEmailViewSessName]) == true and !empty($_SESSION[$testEmailViewSessName])) {
                        $responseData[$testEmailViewSessName] = $_SESSION[$testEmailViewSessName];
                        //reset the testEmailViewSessName items in session
                        $_SESSION[$testEmailViewSessName] = null;
                    }
                }
            }

            return $responseData;
        }
    }

    /*
    * Generate uid 
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('__generateUID')) {
        function __generateUID()
        {
            return md5(uniqid(rand(), true));
        }
    }

    /*
    * Customized GetText string
    *
    * @param string $string
    * @param array $replaceValues
    * 
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('__') and !config('__tech.gettext_fallback')) {
        function __($string, $replaceValues = [])
        {
            if (function_exists('gettext') and getenv('LC_ALL') !== false) {
                $string = gettext($string);
            }

            // Check if replaceValues exist
            if (!empty($replaceValues) and is_array($replaceValues)) {
                $string = strtr($string, $replaceValues);
            }

            return $string;
        }
    }

    /*
    * Load DataTable Helper 0.2.1 - 03 JUN 2015
    * 
    * helper function to load datatable.
    *
    * @param array $data - for request response
    * 
    * @return void.
    *-------------------------------------------------------- */

    if (!function_exists('__dataTable')) {
        function __dataTable($sourceData, $dataFormat = [], $options = [])
        {
            $data = [];

            if (Session::has('additional')) {
                $data['additional'] = Session::get('additional');
            }

            $rawData = $sourceData['data'];

            $enhancedData = [];

            foreach ($rawData as $key) {
                $newDataFormat = [];

                if (!empty($dataFormat)) {
                    foreach ($dataFormat as $dataItemKey => $dataItemValue) {
                        if (is_numeric($dataItemKey)) {
                            $newDataFormat[ $dataItemValue ] = $key[ $dataItemValue ];
                        } elseif (is_callable($dataItemValue)) {
                            $newDataFormat[ $dataItemKey ] = call_user_func($dataItemValue, $key);
                        } else {
                            $newDataFormat[ $dataItemKey ] = $key[ $dataItemValue ];
                        }
                    }
                } else {
                    $newDataFormat = $key;
                }

                $primaryKey = array_key_exists('_id', $key) ? '_id' : 'id';

                $newDataFormat['DT_RowId'] = 'rowid_'.$key[$primaryKey];

                $enhancedData[] = $newDataFormat;
            }

            $dataTablesData = array(
                    'recordsTotal' => $sourceData['total'],
                    'data' => $enhancedData,
                    'recordsFiltered' => $sourceData['total'],
                    'draw' => (int) Request::get('draw'),
                );

            $data['response_token'] = (int) Request::get('fresh');

            $data = array_merge($data, $dataTablesData);

            if (!empty($options)) {
                $data['_options'] = $options;
            }

            // __pr() to print in console
            if (env('APP_DEBUG', false) == true) {
                $prSessItemName = '__pr';

                if (isset($_SESSION[$prSessItemName]) == true and !empty($_SESSION[$prSessItemName])) {
                    $data['__dd'] = true;
                    $data[$prSessItemName] = $prSessItemName;
                    // set for response              
                    $data[$prSessItemName] = $_SESSION[$prSessItemName];
                    //reset the __pr items in session
                    $_SESSION[$prSessItemName] = [];
                }

                $clogSessItemName = '__clog';

                if (isset($_SESSION[$clogSessItemName]) == true and !empty($_SESSION[$clogSessItemName])) {
                    $data['__dd'] = true;
                    $data[$clogSessItemName] = $clogSessItemName;
                    // set for response              
                    $data[$clogSessItemName] = $_SESSION[$clogSessItemName];
                    //reset the __clog items in session
                    $_SESSION[$clogSessItemName] = [];
                }

                // email view debugging
                if (env('MAIL_VIEW_DEBUG', false) == true) {
                    $testEmailViewSessName = '__emailDebugView';
                    if (isset($_SESSION[$testEmailViewSessName]) == true and !empty($_SESSION[$testEmailViewSessName])) {
                        $data[$testEmailViewSessName] = $_SESSION[$testEmailViewSessName];
                        //reset the testEmailViewSessName items in session
                        $_SESSION[$testEmailViewSessName] = null;
                    }
                }
            }

            unset($enhancedData, $rawData, $sourceData, $dataFormat, $dataTablesData);

            return Response::json($data);
        }
    }

    /*
      * Minify and load view
      *
      * @return view
      *---------------------------------------------------------------- */

    if (!function_exists('__loadView')) {
        function __loadView($viewName, $data = [])
        {
            $output = View::make($viewName, $data)->render();

            if (!env('APP_DEBUG', false)) {
                $filters = array(
                    '/(?<!\S)\/\/\s*[^\r\n]*/' => '',  // Remove comments in the form /* */
                    '/\s{2,}/' => ' ', // Shorten multiple white spaces
                    '/(\r?\n)/' => '',  // Collapse new lines
                );

                return preg_replace(
                    array_keys($filters),
                    array_values($filters),
                    $output
                );
            } else {
                $clogSessItemName = '__clog';

                if (isset($_SESSION[$clogSessItemName]) == true and !empty($_SESSION[$clogSessItemName])) {
                    $responseData = [];

                    $responseData['__dd'] = true;
                    $responseData['__clogType'] = 'NonAjax';
                    $responseData[$clogSessItemName] = $clogSessItemName;
                    // set for response              
                    $responseData[$clogSessItemName] = $_SESSION[$clogSessItemName];
                    //reset the __clog items in session
                    $_SESSION[$clogSessItemName] = [];

                    $output = $output.'<script>__globals.clog('.json_encode($responseData).')</script>';
                }
            }

            return $output;
        }
    }

    /*
    * NOTE: This helper function is deprecated as of 24 JUN 2016 instead use batchUpdate of CoreModel function
    * 
    * Batch Update
    *
    * @param string $tableName     - DB Table Name
    * @param string $data          - Data to update along with index key passed 
    *                                using 3rd parameter
    * @param string $index         - Index key
    *
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('__dbBatchUpdate')) {
        function __dbBatchUpdate($tableName, array $data, $index)
        {
            if (empty($tableName) or empty($data) or empty($index)) {
                throw new Exception('Invalid data or index');
            }

            return DB::transaction(function () use ($tableName, $data, $index) {

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
        }
    }

    /*
      * Get actual file name created by Yesset utility
      *
      * @return view
      *---------------------------------------------------------------- */

    if (!function_exists('__yesset')) {
        function __yesset($file)
        {
            $files = glob($file);

            if (empty($files)) {
                throw new Exception('Yesset file not found - '.$file.' 
                    Check * in file name.');
            }

            $getFileName = $files[0];

            return url('/').'/'.$getFileName;
        }
    }

    /*
      * NOTE: This helper function is deprecated as of 24 JUN 2016 instead use engineReaction of CoreEngine function
      * 
      * Send reaction from Engine mostly to Controllers
      *
      * @param Array    $reactionCode  - Reaction from Repo
      * @param Array    $data          - Array of data if needed
      * @param String   $message       - Message if any
      * 
      * @return array
      *---------------------------------------------------------------- */

    if (!function_exists('__engineReaction')) {
        function __engineReaction($reactionCode, $data = null, $message = null)
        {
            //
            if (is_array($reactionCode) === true) {
                $message = $reactionCode[2];
                $data = $reactionCode[1];
                $reactionCode = $reactionCode[0];
            }

            if (__isValidReactionCode($reactionCode) === true) {
                return [
                    'reaction_code' => (integer) $reactionCode,
                    'data' => $data,
                    'message' => $message,
                ];
            }

            throw new Exception('__engineReaction:: Invalid Reaction Code!!');
        }
    }

    /*
      * Process response & send API response
      *
      * @param Integer  $engineReaction - Engine reaction 
      * @param Array    $responses      - Response Messages as per reaction code
      * @param Array    $data           - Additional Data for success
      * 
      * @return array
      *---------------------------------------------------------------- */

    if (!function_exists('__processResponse')) {
        function __processResponse($engineReaction, $messageResponses = [],
            $data = [],
            $appendEngineData = false)
        {
            if (__isValidReactionCode($engineReaction) === true) {
                return __apiResponse($data, $engineReaction);
            }

            if (is_array($engineReaction) === false or (
                        array_key_exists('reaction_code',
                            $engineReaction) === false
                        and array_key_exists('data',
                            $engineReaction) === false
                        and array_key_exists('message',
                            $engineReaction) === false
                )) {
                throw new Exception('__processResponse:: Invalid Engine Reaction');
            }

            $reactionCode = $engineReaction['reaction_code'];
            $reactionMessage = $engineReaction['message'];

            // Use message if sent from EngineReaction
            if (__isEmpty($reactionMessage) === false) {
                $data['message'] = $reactionMessage;
            // else use process response messages
            } elseif ($messageResponses and array_key_exists($reactionCode, $messageResponses)) {
                $data['message'] = $messageResponses[$reactionCode];
            }

            if ($data === true or $appendEngineData === true) {
                if (is_array($data) === false or empty($data) === true) {
                    $data = [];
                }

                $dataFromReaction = $engineReaction['data'];

                if (__isEmpty($dataFromReaction) === false) {
                    if (is_array($dataFromReaction)
                        or is_object($dataFromReaction)) {
                        $data = array_merge($data, (array) $dataFromReaction);
                    }
                }
            }

            return __apiResponse($data, $reactionCode);
        }
    }

    /*
      * Check isset & __isEmpty & return the result based on values sent
      *
      * @param Mixed  $data  - Mixed data - Note: Should no used direct function etc
      * @param Mixed  $ifSetValue  - Value if result is true
      * @param Mixed  $ifNotSetValue  - Value if result is false
      * 
      * @return array
      *---------------------------------------------------------------- */

    if (!function_exists('__ifIsset')) {
        function __ifIsset(&$data, $ifSetValue = '', $ifNotSetValue = '')
        {
            // check if value isset & not empty
            if ((isset($data) === true) and (__isEmpty($data) === false)) {
                if (is_callable($ifSetValue) === true) {
                    return call_user_func($ifSetValue, $data);
                } elseif ($ifSetValue === true) {
                    return $data;
                } elseif ($ifSetValue !== '') {
                    return $ifSetValue;
                }

                return true;
            } else {
                if (is_callable($ifNotSetValue) === true) {
                    return call_user_func($ifNotSetValue);
                } elseif ($ifNotSetValue !== '') {
                    return $ifNotSetValue;
                }

                return false;
            }
        }
    }

    /*
      * Customized isEmpty
      *
      * @param Mixed  $data  - Mixed data
      * 
      * @return array
      *---------------------------------------------------------------- */

    if (!function_exists('__isEmpty')) {
        function __isEmpty($data)
        {
            if (empty($data) === false) {
                if (($data instanceof Illuminate\Database\Eloquent\Collection
                        or $data instanceof Illuminate\Pagination\Paginator
                        or $data instanceof Illuminate\Pagination\LengthAwarePaginator
                        or $data instanceof Illuminate\Support\Collection)
                    and ($data->count() <= 0)) {
                    return true;
                } elseif (is_object($data)) {
                    $data = (array) $data;

                    return empty($data);
                }

                return false;
            }

            return true;
        }
    }

    /*
      * Customized isEmpty
      *
      * @param Integer  $reactionCode  - Reaction Code
      * 
      * @return bool
      *---------------------------------------------------------------- */

    if (!function_exists('__isValidReactionCode')) {
        function __isValidReactionCode($reactionCode)
        {
            if (is_integer($reactionCode) === true
                and array_key_exists($reactionCode,
                    config('__tech.reaction_codes')) === true) {
                return true;
            }

            return false;
        }
    }

    /*
      * listen Query events 
      *---------------------------------------------------------------- */
    if (env('APP_DEBUG', false) == true) {
        Event::listen('Illuminate\Database\Events\QueryExecuted', function ($event) {

            $bindings = $event->bindings;

            if (count($bindings) > 0) {
                // Format binding data for sql insertion
                foreach ($bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } elseif (is_string($binding)) {
                        $bindings[$i] = "'$binding'";
                    }
                }

                $clogItems['SQL__Bindings'] = implode($bindings, ', ');
            }

            // Insert bindings into query
            $query = str_replace(array('%', '?'), array('%%', '%s'), $event->sql);
            $query = vsprintf($query, $bindings);

            $clogItems = ['SQL__Query' => $query];

            $clogItems['SQL__TimeTaken'] = $event->time;

            __clog($clogItems);
        });
    }
