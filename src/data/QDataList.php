<?php

/** Query Able Data List Use Linq */
class QDataList extends IDataList {

    public function __construct( ) {
        parent::__construct([]);
    }

    public function Select($param) {
        return array_map($param, $this->data);
    }

    public function Where($func): array {
        return array_filter($this->data, $func);
    }

    public function WhereValue($name, $value): array {
        return array_filter($this->data, function ($x) use ($name, $value) {
            return $x[$name] == $value;
        });
    }

    public function First() {
        return reset($this->data);
    }

    public function FirstOrDefault() {
        return !empty($this->data) ? reset($this->data) : null;
    }

    public function Last() {
        return end($this->data);
    }


    public function LastOrDefault() {
        return !empty($this->data) ? end($this->data) : null;
    }

//    public function Single() {
//        $singleItem = null;
//        foreach ($myList as $item) {
//            if ($item->property == $value) {
//                if ($singleItem === null) {
//                    $singleItem = $item;
//                } else {
//                    throw new Exception("More than one item found.");
//                }
//            }
//        }
//    }

//    public function SingleOrDefault() {
//        $singleOrDefaultItem = null;
//        foreach ($myList as $item) {
//            if ($item->property == $value) {
//                if ($singleOrDefaultItem === null) {
//                    $singleOrDefaultItem = $item;
//                } else {
//                    return null; // More than one item found, return null.
//                }
//            }
//        }
//    }

    public function Any($fnc): bool {
        return !empty(array_filter($this->data, $fnc));
    }

    public function AnyByValue($name, $value): bool {
        return !empty(array_filter($this->data, function ($x) use ($name, $value) {
            return $x[$name] == $value;
        }));
    }

    /** Reverse Checking return $x->property != $value; */
    public function All($fnc): bool {
        return empty(array_filter($this->data, $fnc));
    }

    public function AllByValue($name, $value): bool {
        return empty(array_filter($this->data, function ($x) use ($name, $value) {
            return $x[$name] != $value;
        }));
    }

    public function Distinct($fnc): array {
        return array_unique($this->data, SORT_REGULAR); // Use SORT_REGULAR for object comparison.
    }

    public function OrderBy($name): bool {
        return usort($this->data, function ($x, $y) use ($name) {
            return $x[$name] <=> $y[$name];
        });
    }

    public function OrderByDescending($name): bool {
        return usort($this->data, function ($x, $y) use ($name) {
            return $y[$name] <=> $x[$name];
        });
    }

    public function GroupBy($name): array {
        $grouped = [];
        foreach ($this->data as $item) {
            $grouped[$item[$name]][] = $item;
        }
        return $grouped;
    }

    public function Join($otherList, $name): array {
        $joined = [];
        foreach ($this->data as $x) {
            foreach ($otherList as $y) {
                if ($x[$name] == $y[$name]) {
                    $joined[] = ['x' => $x, 'y' => $y];
                }
            }
        }
        return $joined;
    }


    /** Select All Sub Items Array In One Array */
    public function SelectMany($subItems): array {
        $flattened = [];
        foreach ($this->data as $item) {
            foreach ($item[$subItems] as $subItem) {
                $flattened[] = $subItem;
            }
        }
        return $flattened;
    }

    function Take($num): array {
        return array_slice($this->data, 0, $num);
    }

    function Skip($num): array {
        return array_slice($this->data, $num);
    }

}