<?php
/**
 * 分页类
 * @author  xiaojiong & 290747680@qq.com
 * @date 2011-08-17
 *
 * show(2)  1 ... 62 63 64 65 66 67 68 ... 150
 * 分页样式
 * #page{font:12px/16px arial}
 * #page span{float:left;margin:0px 3px;}
 * #page a{float:left;margin:0 3px;border:1px solid #ddd;padding:3px 7px; text-decoration:none;color:#666}
 * #page a.now_page,#page a:hover{color:#fff;background:#05c}
*/

class Core_Lib_Page
{
    public     $first_row;        //起始行数

    public     $list_rows;        //列表每页显示行数

    protected  $total_pages;      //总页数

    protected  $total_rows;       //总行数

    protected  $now_page;         //当前页数

    protected  $method  = 'defalut'; //处理情况 Ajax分页 Html分页(静态化时) 普通get方式

    protected  $parameter = '';

    protected  $page_name;        //分页参数的名称

    protected  $ajax_func_name;

    public     $plus = 3;         //分页偏移量

    protected  $url;

    public     $seach;

    const PAGESIZE = 5;         //页大小


    /**
     * 构造函数
     * @param unknown_type $data
     */
    public function __construct($data = array())
    {
        $this->total_rows = $data['total_rows'];

        $this->parameter         = !empty($data['parameter']) ? $data['parameter'] : '';
        $this->list_rows         = !empty($data['list_rows']) && $data['list_rows'] <= 100 ? $data['list_rows'] : 15;
        $this->total_pages       = intval(ceil($this->total_rows / $this->list_rows)); 

        $this->page_name         = !empty($data['page_name']) ? $data['page_name'] : 'page';
        $this->ajax_func_name    = !empty($data['ajax_func_name']) ? $data['ajax_func_name'] : '';

        $this->method           = !empty($data['method']) ? $data['method'] : '';


        /* 当前页面 */
        if(!empty($data['now_page']))
        {
            $this->now_page = intval($data['now_page']);
        }else{
            $this->now_page   = !empty($_GET[$this->page_name]) ? intval($_GET[$this->page_name]):1;
        }
        $this->now_page   = $this->now_page <= 0 ? 1 : $this->now_page;


        if(!empty($this->total_pages) && $this->now_page > $this->total_pages)
        {
            $this->now_page = $this->total_pages;
        }
        $this->first_row = $this->list_rows * ($this->now_page - 1);
    }

    /**
     * 得到当前连接
     * @param $page
     * @param $text
     * @return string
     */
    protected function _get_link($page,$text)
    {
        switch ($this->method) {
            case 'ajax':
                $parameter = '';
                if($this->parameter)
                {
                    $parameter = ','.$this->parameter;
                }
                return '<a onclick="' . $this->ajax_func_name . '(\'' . $page . '\''.$parameter.')" href="javascript:void(0)">' . $text . '</a>' . "\n";
            break;

            case 'html':
                $url = str_replace('?', $page,$this->parameter);
                return '<a href="' .$url . '">' . $text . '</a>' . "\n";
            break;

            default:
                return '<a href="' . $this->_get_url($page) . '">' . $text . '</a>' . "\n";
            break;
        }
    }


    /**
     * 设置当前页面链接
     */
    protected function _set_url()
    {
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$this->page_name]);
            unset($params['seach']);
            unset($params['name']);
            unset($params['data']);
            unset($params['status']);
            unset($params['tui']);
            unset($params['shou']);
            unset($params['pay']);
            unset($params['supp']);
            unset($params['datestart']);
            unset($params['dateend']);
            unset($params['number']);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        if(!empty($params))
        {
            $url .= '&';
        }
        $this->url = $url;
    }

    /**
     * 得到$page的url
     * @param $page 页面
     * @return string
     */
    protected function _get_url($page)
    {
        if($this->url === NULL)
        {
            $this->_set_url();
        }
    //  $lable = strpos('&', $this->url) === FALSE ? '' : '&';
     if($this->seach){
         return $this->url . $this->page_name . '=' . $page . $this->seach;
     }else{
        return $this->url . $this->page_name . '=' . $page;
     }

    }


    /**
     * 得到第一页
     * @return string
     */
    public function first_page($name = '首页')
    {

            return $this->_get_link('1', $name);

    }

    /**
     * 最后一页
     * @param $name
     * @return string
     */
    public function last_page($name = '尾页')
    {

            return $this->_get_link($this->total_pages, $name);

    }

    /**
     * 上一页
     * @return string
     */
    public function up_page($name = '上一页')
    {
        if($this->now_page != 1)
        {
            return $this->_get_link($this->now_page - 1, $name);
        }
        return '';
    }

    /**
     * 下一页
     * @return string
     */
    public function down_page($name = '下一页')
    {
        if($this->now_page < $this->total_pages)
        {
            return $this->_get_link($this->now_page + 1, $name);
        }
        return '';
    }

    /**
     * 分页样式输出
     * @param $param
     * @return string
     */
    public function show($param = 1)
    {
        if($this->total_rows < 1)
        {
            return '';
        }

        $className = 'show_' . $param;

        $classNames = get_class_methods($this);

        if(in_array($className, $classNames))
        {
            return $this->$className();
        }
        return '';
    }
    protected function show_3()
    {
        $plus = $this->plus;
        if( $plus + $this->now_page > $this->total_pages)
        {
            $begin = $this->total_pages - $plus * 2;
        }else{
            $begin = $this->now_page - $plus;
        }
        $begin = ($begin >= 1) ? $begin : 1;
        $return .='<div class="row"><form class="form-inline warestatus-form1 warestatus-form2"><div class="form-group"><label for="exampleInputName2"class="labelname">每页：</label><select class="form-control waregood-status rrow" id="exampleInputName2">';

            foreach (array(1,2,3,4,5) as  $value) {
                if($this->list_rows==$value){
                $return .='<option value="';
                $return .=$value;
                $return .='" selected="true">';
                $return .=$value;
                $return .='</option>';
                }else{
                    $return .='<option value="';
                $return .=$value;
                $return .='">';
                $return .=$value;
                $return .='</option>';
                }

            }
            $return .='</select></div>';
            $return .='<div class="form-group"><ul class="warestatus-page"><li>';
            $return .= $this->first_page()."\n";
            $return .= '</li><li class="previous">';
            $return .= $this->up_page()."\n";
            $return .= '</li><li><label for="exampleInputName2"class="labelname">第</label><span id="pps">';
            $return .=$this->now_page;
            $return .= '</span><label for="exampleInputName2"class="labelname">页 (共<span>';
            $return .= $this->total_pages;
            $return .= '</span>页<span>';
            $return .= $this->total_rows;
            $return .= '</span>条)</label></li><li class="next">';
            $return .=   $this->down_page()."\n";
            $return .= '</li><li>';
            $return .= $this->last_page()."\n";
            $return .='</li></ul></div></form></div>';

        return $return;
    }
}