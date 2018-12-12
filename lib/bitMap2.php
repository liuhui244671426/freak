<?php

class lib_bitMap2
{
    public $fh, $size;

    public function __construct($bitmap)
    {
        $this->fh = fopen($bitmap, file_exists($bitmap) ? 'r+' : 'w+');
        clearstatcache(true, $bitmap);
        $this->size = filesize($bitmap);
    }

    /**
     * @param $offset
     * @param int $value
     * @return bool
     */
    public function setBit($offset, $value = 1)
    {
        if ($value !== 0 && $value !== 1) return FALSE;
        if ($offset > $this->size * 8) $this->_append($offset);
        fseek($this->fh, ceil($offset / 8) - 1);
        $byte = fread($this->fh, 1);
        $mask = pack('C', 256 >> (fmod($offset - 1, 8) + 1));
        fseek($this->fh, ftell($this->fh) - 1);
        $res = $value ? ($byte | $mask) : ($byte & ~$mask);
        fwrite($this->fh, $res);
        return fflush($this->fh);
    }

    /**
     * @param $offset
     * @return bool|int
     */
    public function getBit($offset)
    {
        if (fseek($this->fh, ceil($offset / 8) - 1)) return FALSE;
        $byte = fread($this->fh, 1);
        $res = $byte & pack('C', 256 >> (fmod($offset - 1, 8) + 1));
        return (!$res || $res === "\0") ? 0 : 1;
    }

    /**
     * @return int
     */
    public function bitCount()
    {
        $count = 0;
        rewind($this->fh);
        while (!feof($this->fh)) {
            $bytes = fread($this->fh, pow(2, 20));
            foreach (unpack('C*', $bytes) as $bin) {
                while ($bin) {
                    $count++;
                    $bin &= ($bin - 1);
                }
            }
        }
        return $count;
    }

    /**
     * @param $offset
     */
    private function _append($offset)
    {
        fseek($this->fh, 0, SEEK_END);
        list($size, $step) = [ceil($offset / 8), pow(2, 20)];
        $append = $size - $this->size;
        $value = str_repeat("\0", $step);
        for ($n = 0; $n < floor($append / $step); $n++) {
            fwrite($this->fh, $value);
        }
        fwrite($this->fh, str_repeat("\0", $append % $step));
        $this->size = $size;
    }

    /**
     * close file
     */
    public function __destruct()
    {
        fclose($this->fh);
    }
}