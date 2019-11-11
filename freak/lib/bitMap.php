<?php
/**
 * (c) Dmytro Sokil <dmytro.sokil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
//namespace Sokil;
/**
 * Instance of binary map
 */
defined('FREAK_ACCESS') or exit('Access Denied');

class freak_lib_bitMap
{
    /**
     * @var int
     */
    private $bitmap;
    /**
     * @param int $bitmap
     */
    public function __construct($bitmap = 0)
    {
        $this->bitmap = (int)$bitmap;
    }
    /**
     * @param int $index
     * @return bool
     */
    public function isBitSet($index)
    {
        return (bool)($this->bitmap & (1 << $index));
    }
    /**
     * @param int $mask
     * @return bool
     */
    public function isAnyMaskBitSet($mask)
    {
        return ($this->bitmap & $mask) > 0;
    }
    /**
     * @param int $mask
     * @return bool
     */
    public function isAllMaskBitsSet($mask)
    {
        return $mask === ($this->bitmap & $mask);
    }
    /**
     * @param int $index
     * @return object freak_lib__bitMap
     */
    public function setBit($index)
    {
        $this->bitmap = $this->bitmap | (1 << $index);
        return $this;
    }
    /**
     * @param int[] $indexList
     * @return object freak_lib__bitMap
     */
    public function setBits(array $indexList)
    {
        $mask = 0;
        foreach($indexList as $index) {
            // | 按位或
            // << 将1向左移动 index 次,每一次移动都表示 "乘以2"
            $mask = $mask | (1 << $index);
        }

        $this->setBitsByMask($mask);

        return $this;
    }
    /**
     * @param int $mask
     * @return object freak_lib__bitMap
     */
    public function setBitsByMask($mask)
    {
        $this->bitmap = $this->bitmap | $mask;
        return $this;
    }
    /**
     * @param int $index
     * @return object freak_lib__bitMap
     */
    public function unsetBit($index)
    {
        $this->bitmap = $this->bitmap & ~(1 << $index);
        return $this;
    }
    /**
     * @param int[] $indexList
     * @return object freak_lib__bitMap
     */
    public function unsetBits(array $indexList)
    {
        $mask = 0;
        foreach($indexList as $index) {
            $mask = $mask | (1 << $index);
        }

        $this->unsetBitsByMask($mask);

        return $this;
    }
    /**
     * @param int $mask
     * @return object freak_lib__bitMap
     */
    public function unsetBitsByMask($mask)
    {
        $this->bitmap = $this->bitmap & ~$mask;
        return $this;
    }
    /**
     * @return object freak_lib__bitMap
     */
    public function invert()
    {
        $this->bitmap = ~$this->bitmap;
        return $this;
    }
    /**
     * @return int
     */
    public function getInt()
    {
        return $this->bitmap;
    }
    /**
     * @return string
     */
    public function getBinary()
    {
        return decbin($this->bitmap);
    }
    /**
     * @param object $bitmap
     * @return bool
     */
    public function equals(freak_lib_bitMap $bitmap)
    {
        return $this->bitmap === $bitmap->getInt();
    }
    /**
     * Add two bitmaps.
     *
     * @param freak_lib_bitMap $bitmap
     * @return freak_lib_bitMap
     */
    public function add(freak_lib_bitMap $bitmap)
    {
        return new freak_lib_bitMap($this->bitmap + $bitmap->getInt());
    }
}