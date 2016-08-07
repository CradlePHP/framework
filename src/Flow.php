<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework;

/**
 * General flow steps
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Flow
{
    /**
     * Brings the results back to staging
     *
     * @return Closure
     */
    public static function reset()
    {
        return function ($request, $response) {
            $results = $response->getResults();
            
            if (empty($results)) {
                return;
            }
            
            foreach ($results as $key => $value) {
                //it's quite impossible for a POST or 
                //GET to be a number for example
                if (is_numeric($key)) {
                    continue;
                }

                $request->setStage($key, $value);
            }
        };
    }

    /**
     * Brings the stage to results
     *
     * @return Closure
     */
    public static function forward()
    {
        return function ($request, $response) {
            $stage = $request->getStage();
            
            if (empty($stage)) {
                return;
            }
            
            foreach ($stage as $key => $value) {
                $response->setResults($key, $value);
            }
        };
    }
}
