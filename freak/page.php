<?php
defined('FREAK_ACCESS') or exit('Access Denied');

//分页工具 db->redis 非聚簇
class freak_page
{
    protected $rds = null;
    protected $db = null;
    protected $pk_id = null;//主键
    protected $sort_id = null;//排序键
    protected $cache_set_key = 'freak_page_index';//集合索引
    protected $cache_item_key = 'freak_page_item_%s';//单条数据,占位符$pk_id
    protected $cache_expire = 86400;

    public function __construct(Array $arr_rules = [])
    {
        $this->pk_id = $arr_rules['pk_id'] ? $arr_rules['pk_id'] : 'id';
        $this->sort_id = $arr_rules['sort_id'] ? $arr_rules['sort_id'] : 'id';
        $this->cache_expire = $arr_rules['cache_expire'] ? $arr_rules['cache_expire'] : 86400;
        $this->cache_item_key = $arr_rules['cache_item_key'] ? $arr_rules['cache_item_key'] : 'freak_page_item_%s';
        $this->cache_set_key = $arr_rules['cache_set_key'] ? $arr_rules['cache_set_key'] : 'freak_page_index';
        return true;
    }

    public function get_rules()
    {
        return [
            'pk_id'          => $this->pk_id,
            'sort_id'        => $this->sort_id,
            'cache_expire'   => $this->cache_expire,
            'cache_item_key' => $this->cache_item_key,
            'cache_set_key'  => $this->cache_set_key,
        ];
    }

    /*
     * 连接 redis
     * */
    public function conn_redis(freak_redis $redis_factory)
    {
        $this->rds = $redis_factory;
        return true;
    }

    /*
     * 连接 db
     * */
    public function conn_db(freak_pdo $db_factory)
    {
        $this->db = $db_factory;
        return true;
    }

    /*
     * 设置单条数据
     * */
    public function set_rds_item($data, $pk_id_value)
    {
        $key = sprintf($this->cache_item_key, $pk_id_value);
        freak_log::write('item key : ' . $key);
        return $this->rds->set($key, json_encode($data), $this->cache_expire);
    }

    /*
     * 设置 redis zSet
     * */
    public function set_rds_index($db_arr)
    {
        if (empty($db_arr) || !is_array($db_arr)) return false;
        freak_log::write('set key : ' . $this->cache_set_key);
        foreach ($db_arr as $k => $item) {
            $score = $item[ $this->sort_id ];//按照排序键
            $this->rds->zadd($this->cache_set_key, $score, $item[ $this->pk_id ]);
            $this->set_rds_item($item, $item[ $this->pk_id ]);
        }
        return true;
    }

    /*
     * 获取 redis zSet (从小到大)
     * page 从 1 开始
     * */
    public function get_rds_index($page, $limit)
    {
        $start = ($page - 1) * $limit;
        $end = $start + $limit - 1;
        return $this->rds->ZRANGE($this->cache_set_key, $start, $end, true);
    }

    /*
     * 获取 redis zSet (从大到小)
     * page 从 1 开始
     * */
    public function get_rds_index_rev($page, $limit)
    {
        $start = ($page - 1) * $limit;
        $end = $start + $limit - 1;
        return $this->rds->ZREVRANGE($this->cache_set_key, $start, $end, true);
    }

    /*
     * 获取 redis zSet 总数
     * */
    public function get_rds_index_count()
    {
        return $this->rds->ZCARD($this->cache_set_key);
    }

    /*
     * 获取 redis 单条数据
     * */
    public function get_rds_item($index_id)
    {
        return json_decode($this->rds->get(sprintf($this->cache_item_key, $index_id)), true);
    }

    /*
     * 获取 redis 多条数据
     * */
    public function get_rds_items(Array $index_id)
    {
        if (!is_array($index_id)) {
            return false;
        }
        $keys = array_map(function ($id) {
            return sprintf($this->cache_item_key, $id);
        }, array_keys($index_id));
        $list = $this->rds->mget($keys);
        $rtn = array_map(function ($item) {
            return json_decode($item, 1);
        }, $list);
        return $rtn;
    }

    /*
     * 从 db 获取数据
     * */
    public function get_db_rows($sql, $params)
    {
        return $this->db->query($sql, $params);
    }
}