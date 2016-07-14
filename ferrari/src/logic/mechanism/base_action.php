<?php

/** 
 * @brief  要求是Post的action基类
 */
abstract class XPostAction extends XAction
{
    public function _before($request, $xcontext)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo ResultSet::jfail(405, "HTTP METHOD is not suported for this request");
            return false;
        }
    }
}

/** 
 * @brief  分页获取数据的Action基类
 */
abstract class BaseListAction extends XAction
{
    const DEFAULT_LIMIT = 20;
    const MAX_LIMIT = 100;

    public function _before($request, $xcontext)
    {
        return self::checkBaseList($request, $xcontext);
    }

    public static function checkBaseList($request, $xcontext)
    {
        $start = intval($request->start);
        $limit = intval($request->limit);

        $start = $start < 0 ? 0 : $start;
        $limit = $limit <= 0 ? self::DEFAULT_LIMIT : $limit;

        if ($limit > self::MAX_LIMIT) {
            echo ResultSet::jfail(400, "limit is too large, should less than " . self::MAX_LIMIT);
            return false;
        }

        $xcontext->start = $start;
        $xcontext->limit = $limit;
        return true;
    }
}

/** 
* @brief  加载更多时，使用的Action基类
*/
abstract class BaseLoaderAction extends XAction
{
    const DEFAULT_LIMIT = 20;
    const MAX_LIMIT = 100;

    public function _before($request, $xcontext)
    {
        return self::checkBaseLoader($request, $xcontext);
    }

    public static function checkBaseLoader($request, $xcontext)
    {
        $score = 0 + $request->score;  // transform to number
        $limit = intval($request->limit);

        $limit = $limit <= 0 ? self::DEFAULT_LIMIT : $limit;

        if ($limit > self::MAX_LIMIT) {
            echo ResultSet::jfail(400, "limit is too large, should less than " . self::MAX_LIMIT);
            return false;
        }

        $xcontext->score = $score;
        $xcontext->limit = $limit;
        return true;
    }
}
