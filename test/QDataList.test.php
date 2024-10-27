<?php

// >> Initialize


include_once "../data/IDataList.php";
include_once "../data/QDataList.php";
include "./lib/TestReportBuilder.php";
include "./api/SampleItem.php";

$test = new TestReportBuilder();
$SampleData = new SampleItem();
// >> Briefing
$Briefing = $test->Section("Briefing");


$Briefing->Create("h2", "List of Tests")
    ->List(
        "Select"


    );

// >> TEST BEGIN

$list1 = new QDataList([]);
$test->Report("Start", $list1->ToString(), "color:lightblue;");

$SampleData->LoadData($list1);
$test->Report("Load", $list1->ToString(), "color:lightblue;");


// ## Select
$Select = $list1->Select(function ($p) {
    return $p["price"];
});

$Select = new IDataList($Select);
$test->AddTitle("Select ", 2)
    ->Create("pre code", <<<'text'
        $Select = $list1->Select(function ($p) {
            return $p["price"];
        });
    text
    )
    ->Report("Select Price", $Select->ToString(),  )
    ->TestResultSuccess("Complete")
;

// ## Where

// >> Print
echo $test->AsSideContent($Briefing->Result);

