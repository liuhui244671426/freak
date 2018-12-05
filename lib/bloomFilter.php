<?php

/**
 *
 * 布隆过滤
 */
class lib_bloomFilter
{
    protected $m; // block size of the bit array
    protected $n; // number of strings to hash
    protected $k; // number of hashing functions
    protected $f; // false positive 的比率
    protected $bitSet; // hashing block with size m

    public function __construct($mInit = 1, $nInit = 1)
    {
        //容器体积
        $this->m = $mInit;
        //将容器全部填满 false
        $this->bitSet = array_fill(0, $this->m, false);
        //元素个数
        $this->n = $nInit;

        /*
         * 计算最优的hash函数个数
         * 文献证明给定的m、n，当 k = log(2)* m/n 时出错的概率是最小的
         */
        $this->k = ceil(($this->m / $this->n) * log(2));
    }

    public function hashCode($str)
    {
        $res = [];

        //由于CRC32产生校验值时源数据块的每一bit（位）都会被计算，所以数据块中即使只有一位发生了变化，也会得到不同的CRC32值。
        $seed = crc32($str);
        // 为 mt_rand 种下 seed
        mt_srand($seed);

        for ($i = 0; $i < $this->k; $i++) {
            $t = mt_rand(0, $this->m - 1);
            $res[] = $t;
        }
        return $res;
    }

    public function add($key)
    {
        foreach ($this->hashCode($key) as $codeBit) {
            $this->bitSet[ $codeBit ] = true;
        }
    }

    public function exist($key)
    {
        foreach ($this->hashCode($key) as $codeBit) {
            if ($this->bitSet[ $codeBit ] == false) {
                return false;
            }
        }
        return true;
    }
    /*
     * 返回 "误报率"
     * False Positive的比率：f = (1 – e-kn/m)k
     * */
    public function getFalsePositiveProbability() {
        $exp = (-1 * $this->k * $this->n) / $this->m;
        $this->f = pow(1 - exp($exp),  $this->k);
        return $this->f;
    }
}
