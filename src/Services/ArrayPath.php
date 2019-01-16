<?php

namespace App\Services;

class ArrayPath
{
    private $inlineData = [];

    private $arrayLvl = [];

    private static $separator = '__';

    private $data;

    private $exclude;

    public function __construct(array $data, ?string $separator = null, $exclude = [])
    {
        $separator !== null ? self::$separator = $separator : null;
        $this->data = $data;
        $this->exclude = $exclude;
    }

    private function iterateInline($data, $path = []): void
    {
        foreach ($data as $k => $v) {
            $selfPath = $path;
            if (is_string($k)) {
                $selfPath[] = $k . self::$separator;
            }
            if (is_string($v)) {
                $selfPath[] = $v;
                $this->inlineData[] = [implode($selfPath) => $v];
            } else {
                foreach ($v as $kB => $vB) {
                    $this->iterateInline([$kB => $vB], $selfPath);
                }
            }
        }
    }

    private function exclude($data)
    {
        $formatedData = [];
        if (!empty($this->exclude)) {
            foreach ($data as $k => $d) {
                if (!in_array($d, $this->exclude)){
                    $formatedData[] = $d;
                }
            }
            return $formatedData;
        } else {
            return $data;
        }
    }

    private function iterateArrayLvl($data): void
    {
        foreach ($data as $item) {
            $this->arrayLvl[] = [
                'path' => $this->exclude(explode(self::$separator, key($item))),
                'name' => array_values($item)[0],
            ];
        }
    }

    public function getInlineData(): array
    {
        $this->iterateInline($this->data);
        return $this->inlineData;
    }

    public function getArrayLvl(): array
    {
        if (empty($this->inlineData)) {
            $this->getInlineData();
        }
        $this->iterateArrayLvl($this->inlineData);
        return $this->arrayLvl;
    }
}