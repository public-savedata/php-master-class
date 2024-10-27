<?php

/** Iterator Data List */
class IDataList {
    public array $data;
    public function __construct($arr) {
        $this->data = $arr;
    }

    // >> ADD
    public function Add($obj): void {
        $this->data[] = $obj;
    }

    // >> ADD RANGE
    public function AddRange(array $obj): void {
        $this->data = array_merge($this->data, $obj);
    }

    // >> CLEAR
    public function Clear(): void {
        $this->data = [];
    }

    // >> Contains
    public function Contains($obj): bool {
        return in_array($obj, $this->data, true);
    }

    // >> Count
    public function Count(): int {
        return count($this->data);
    }

    // >> IndexOf
    public function IndexOf($obj): false|int|string {
        return array_search($obj, $this->data, true);
    }

    // >> Insert
    public function Insert($index, $obj): void {
        //  array_push($this->data, 0, 1, [$obj]);
        array_splice($this->data, $index, 0, $obj);
    }

    // >> Remove
    public function Remove($obj): void {
        $index = array_search($obj, $this->data, true);
        if ($index !== false) {
            array_splice($this->data, $index, 1);
            //  unset($this->data[$index]);
            //  $this->data = array_values($this->data);
        }
    }

    // >> RemoveAt
    public function RemoveAt($index): void {
        unset($this->data[$index]);
        $this->data = array_values($this->data);
    }

    // >> Sort
    public function Sort($fnc): void {
        usort($this->data, $fnc);
    }

    // >> ToArray
    public function ToArray(): array {
        return $this->data;
    }

    // >> ForEach
    public function ForEach($fnc): void {
        foreach ($this->data as $obj) {
            $fnc($obj);
        }
    }

    // >> Find
    public function Find($name, $value) {
        foreach ($this->data as $obj) {
            if ($obj[$name] == $value) return $obj;
        }
        return null;
    }

    // >> FindAll
    public function FindAll($func): array {
        return array_filter($this->data, $func);
    }

    // >> FindIndex
    public function FindIndex($name, $value): int {
        foreach ($this->data as $index => $datum) {
            if ($datum[$name] == $value) return $index;
        }
        return -1;
    }

    // >> RemoveAll
    public function RemoveAll($name, $value): void {
        $this->data = array_filter($this->data, function ($obj) use ($name, $value) {
            return $obj[$name] != $value;
        });
    }

    // >> Reverse
    public function Reverse(): void {
        $this->data = array_reverse($this->data);
    }

    public function ToString(): string {
        return Json_encode($this->data, JSON_PRETTY_PRINT);
    }
}