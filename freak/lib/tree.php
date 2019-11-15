<?php
//分页树
class freak_lib_tree
{
    private $idKey = 'id'; //主键的键名
    private $fidKey = 'fid'; //父ID的键名
    private $root = 0; //最顶层fid
    private $data = []; //源数据
    private $treeArray = []; //属性数组

    function __construct($data, $idKey, $fidKey, $root)
    {
        if ($idKey) $this->idKey = $idKey;
        if ($fidKey) $this->fidKey = $fidKey;
        if ($root) $this->root = $root;
        if ($data) {
            $this->data = $data;
            $this->getChildren($this->root);
        }
    }

    public function getTreeArray()
    {
        //去掉键名
        return array_values($this->treeArray);
    }

    /**
     * @param int $root 父id值
     * @return null or array
     */
    private function getChildren($root)
    {
        $children = [];
        foreach ($this->data as &$node) {
            if ($root == $node[ $this->fidKey ]) {
                $tmp = $this->getChildren($node[ $this->idKey ]);
                $node['children'] = $tmp;
                $children[] = $node;

            }
            //只要一级节点
            if ($this->root == $node[ $this->fidKey ]) {
                $this->treeArray[ $node[ $this->idKey ] ] = $node;
            }
        }
        return $children;
    }
}