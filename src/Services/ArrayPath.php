<?php

namespace App\Services;

class ArrayPath
{
    private $inlineData = [];

    private $arrayLvl = [];

    private static $separator = '__';
    private $data;

    public function __construct(array $data, ?string $separator = null)
    {
        $separator !== null ? self::$separator = $separator : null;
        $this->data = $data;
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

    private function iterateArrayLvl($data): void
    {
        foreach ($data as $item) {
            $this->arrayLvl[] = [
                'path' => explode('__', key($item)),
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