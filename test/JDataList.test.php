<?php



include_once "../data/JsonDataList.php";
include_once "lib/TestReportBuilder.php";


$report = new TestReportBuilder();


$report
    ->Add(
        ["h2", "Briefing"],
        ["p", 'Create test for use JsonDataList Class'],
    )
    ->Add(["h3", 'Load Parse And Save'])->List([
        '- [x] Load From File',
        '- [ ] Load From String',
        '- [ ] Save File',
        '- [ ] Check Data Validation',
    ])
    ->Add(["h3", 'Create New Item'])->List([
        '- [ ] Create from Template',
        '- [x] Add Item To Data List',
        '- [ ] Add Item to item\'s sub list',
        '- [ ] Check Data is Valid Via Model',
    ])
    ->Add(["h3", 'Search Over the Items'])->List([
        '- [x] Search By ID',
        '- [x] Search By UID',
    ])
    ->Add(["h3", 'Update Data'])->List([
        '- [ ] Update Item',
        '- [x] Update Field',
        '- [ ] Update item\'s sub Field',
        '- [ ] Update Index',
    ])
    ->Add(["h3", 'Delete Data', 'color:red;'])->List([
        '- [x] Delete Items By ID',
        '- [ ] Delete Items By UID',
        '- [ ] Delete All Items By Command',
    ])
    ->Add(["h3", 'Item State', 'color:orange;'])->List([
        '- [ ] Temp Data',
        '- [ ] invalid Data',
        '- [ ] Valid Data',
        '- [ ] Deleted / Inactive Data',
        '- [ ] Removed Data',
    ]);;
$side = $report->Result;

$report->Clear()->Add(
    ["h2", "TEST JSON DATA LIST"],
);

$Loader = new JsonDataList("api/test-array.json");
$report->Log("File", $Loader->FilePath);
$report->Log("Before Load", json_encode($Loader->DataList));
$Loader->Load();
$report->Log("After Load", json_encode($Loader->DataList));

$report->AddTitle("Test: When Data is Empty ?");
$Loader = new JsonDataList("api/test-datalist.json");
$report->Log("After Load", json_encode($Loader->DataList));


$report->AddTitle("Test: Add New Data ?");
$sampleJson = json_decode('{"title": "sample item", "data":[] }', true);
$Loader->Add($sampleJson);
$report->Log("Example", json_encode($sampleJson));
$report->Log("Result", json_encode($Loader->DataList, JSON_PRETTY_PRINT));

$report->Add(["h4", "Assign New UID"]);

// $Loader->DataList = [];
$Loader->Add($sampleJson, true);
$report
    ->Table(['Loader->Add($sampleJson, true);', "SetUid = true"])
    ->ResultTable(json_encode($Loader->DataList, JSON_PRETTY_PRINT));


// >> GET ITEM

$idTmp = 1;
$ItemIndexTmp = $Loader->SearchById($idTmp);
$report
    ->TitleWithCode("Get Item By Id", (string)$idTmp)
    ->Table(['SearchById($uid)', "Search Index By UID", " $ItemIndexTmp "  ])
    ->ResultTable(json_encode($Loader->DataList[$ItemIndexTmp], JSON_PRETTY_PRINT));;


$uid = $Loader->DataList[1]["data"]["uid"];
$ItemIndexTmp = $Loader->SearchByUid($uid);
$report
    ->TitleWithCode("Get Item By Uid", $uid)
    ->Table(['SearchByUid($uid)', "Search Index By UID", " $ItemIndexTmp "])
    ->ResultTable(json_encode($Loader->DataList[$ItemIndexTmp], JSON_PRETTY_PRINT));;


// >> UPDATE

$report->Add(["h2", "Update Item by ID"]);
$Loader->Update(1, "title", "updated data");

$report
    ->Table(['Update(1, "title", "updated data")', "SetUid = true"])
    ->ResultTable(json_encode($Loader->DataList, JSON_PRETTY_PRINT));

// >> DELETE
$Loader->Delete(1);
$report
    ->Add(["h2", "Delete Data"])
    ->Table(['Delete(1)', "Delete Item By ID"])
    ->ResultTable(json_encode($Loader->DataList, JSON_PRETTY_PRINT));;

echo $report->AsSideContent($side);
