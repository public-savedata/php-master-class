<?php



include_once "../data/IDataList.php";
include "./lib/TestReportBuilder.php";
$subjects = ["apple", "lenovo", "microsoft", "apple", "lenovo", "asus", "microsoft", "samsung", "apple", "asus", "unset"];
$price = [1000, 100, 400, 3000, 500, 200, 200, 2300, 4200, 5, 1000000];


$log = new TestReportBuilder();
function sample_fill(): void {
    global $list, $subjects, $price;
    $list->Clear();
    for ($i = 0; $i < 10; $i++) {
        $list->Add([
                "index" => $i,
                "name" => "item-$i",
                "subject" => $subjects[$i],
                "price" => $price[$i]
            ]
        );
    }
}

function getItem($i): array {
    global $list, $subjects, $price;
    return [
        "index" => $i,
        "name" => "item-$i",
        "subject" => $subjects[$i],
        "price" => $price[$i]
    ];


}

$log->AddTitle("DATA LIST TEST")
    ->Add(
        ["h3", "Failed Requests", "color:red;"],
        ["li" , "Original Data Has Not Change In Changes" ,"color:red;"],
        ["li" , "Insert: Not Working","color:red;"],
        ["li" , "Foreach: No Change" ,"color:red;"],
        ["li" , "Remove All: Not Working", "color:red;"],
    )
    ->Add([
        "p", "New Request"
    ])
    ->List([
        "OrderBy"
    ]);


$arr = [];
$list = new IDataList($arr);

$log->Log("DATA", $list->ToString());
sample_fill();

$log->line("then do Sample fill:");
$log->Log("DATA", $list->ToString());
$log->line("Check Original:")
    ->TestResultFailed("Array should be filled too");

// >> ADD
$log->Add(['h3', '&nbsp; >> ADD', 'color:#fff700;'])
    ->TestResultSuccess("Work During Sample Fill");
// >> ADD RANGE
$list->Clear();
$AddRange01 = [];
for ($i = 0; $i < 3; $i++) {
    $AddRange01[] = [
        "index" => $i,
        "name" => "item-$i",
        "subject" => $subjects[$i],
        "price" => $price[$i]
    ];
}
$list->AddRange($AddRange01);

$log
    ->Add(['h3', '&nbsp; >> ADD RANGE', 'color:#fff700;'])
    ->ResultTable($list->ToString())
    ->TestResultSuccess('AddRange($AddRange01)');

// >> CLEAR
$log->Add(['h3', '&nbsp; >> CLEAR', 'color:#fff700;'])->TestResultSuccess("Work During Sample Fill");;
// >> Contains
sample_fill();
$res = [json_encode($list->Contains(getItem(0))), json_encode($list->Contains(getItem(10))),];
$log->Add(['h3', '&nbsp; >> Contains', 'color:#fff700;'])
    ->Table(['$contains = $list->Contains($AddRange01[0]);', "Contains ", " $res[0] "])
    ->Table(['$contains = $list->Contains($AddRange01[10]);', "Invalid ", " $res[1] "])
    ->TestResultSuccess("Works Well");
// >> Count
sample_fill();
$log->Add(['h3', '&nbsp; >> Count', 'color:#fff700;'])
    ->Log("Count", $list->Count())
    ->TestResultSuccess("Works Well");;
// >> IndexOf
$log->Add(['h3', '&nbsp; >> IndexOf', 'color:#fff700;']);
// >> Insert
$list->Insert(3, getItem(9));
$log->Add(['h3', '&nbsp; >> Insert', 'color:#fff700;'])
    ->ResultTable($list->ToString())
    ->TestResultFailed("Array should be filled too");;
// >> Remove
sample_fill();
for ($j = 2; $j < 8; $j++) {
    $list->Remove(getItem($j));
}
$log->Add(['h3', '&nbsp; >> Remove', 'color:#fff700;'])
    ->ResultTable($list->ToString())
    ->TestResultSuccess("Works Well");;
// >> RemoveAt
sample_fill();
for ($j = 0; $j < 5; $j++) {
    $list->RemoveAt(3);
}
$log->Add(['h3', '&nbsp; >> RemoveAt', 'color:#fff700;'])
    ->ResultTable($list->ToString())
    ->TestResultSuccess("Works Well");;;

// >> Sort
sample_fill();
$list->Sort(function ($a, $b) {
    return $a["price"] - $b["price"];
});
$log->Add(['h3', '&nbsp; >> Sort', 'color:#fff700;'])
    ->ResultTable($list->ToString())
    ->TestResultSuccess("Works Well");
// >> ToArray
$log->Add(['h3', '&nbsp; >> ToArray', 'color:#fff700;'])->TestResultSuccess("Works Well");;
// >> ForEach
sample_fill();
$list->ForEach(function ($a) {
    $a["price"] = 1234;
});
$log->Add(['h3', '&nbsp; >> ForEach', 'color:#fff700;'])
    ->ResultTable($list->ToString())->TestResultFailed("Not Working");
// >> Find
sample_fill();
$FindRes = $list->Find("subject", "samsung");
$log->Add(['h3', '&nbsp; >> Find', 'color:#fff700;'])
    ->ResultTable(json_encode($FindRes))->TestResultSuccess("Works Well");;

// >> FindAll
sample_fill();
$FindAllRes = $list->FindAll(function ($a) {
    return $a["subject"] == "microsoft";
});
$log->Add(['h3', '&nbsp; >> FindAll', 'color:#fff700;'])
    ->ResultTable(json_encode($FindAllRes, JSON_PRETTY_PRINT))
    ->TestResultSuccess("Works Well");;

// >> FindIndex
sample_fill();
$FindIndex = $list->FindIndex("name", "item-6");
$log->Add(['h3', '&nbsp; >> FindIndex', 'color:#fff700;'])
    ->log("Search for Item 6", "[$FindIndex]");
// >> RemoveAll
sample_fill();
$list->RemoveAll("subject", "lenovo");
$log->Add(['h3', '&nbsp; >> RemoveAll', 'color:#fff700;'])
    ->ResultTable(json_encode($FindAllRes, JSON_PRETTY_PRINT))
    ->TestResultFailed("Not Working");

// >> Reverse
sample_fill();
$list->Reverse();
$log->Add(['h3', '&nbsp; >> Reverse', 'color:#fff700;'])
    ->ResultTable($list->ToString());


echo $log->ResultAsArticle();
