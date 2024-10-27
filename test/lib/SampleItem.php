<?php

class SampleItem {

    /**
     * @var string[]
     */
    private array $subjects;
    /**
     * @var int[]
     */
    private array $price;

    public function __construct() {
        $this->subjects = ["apple", "lenovo", "microsoft", "apple", "lenovo", "asus", "microsoft", "samsung", "apple", "asus", "unset"];
        $this->price = [1000, 100, 400, 3000, 500, 200, 200, 2300, 4200, 5, 1000000];
    }

    function LoadData($list): void {

        $list->Clear();
        for ($i = 0; $i < 10; $i++) {
            $list->Add($this->GetItem($i));
        }
    }

    function getItem($i): array {
        return [
            "index" => $i,
            "name" => "item-$i",
            "subject" => $this->subjects[$i],
            "price" => $this->price[$i]
        ];
    }


}