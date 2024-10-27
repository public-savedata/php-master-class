<?php



include_once "../mvc/NavUrl.php";
include_once "lib/TestReportBuilder.php";
$CurrentLink = "";
$Nav = new NavUrl();
$TestResult = new TestReportBuilder();


$TestResult
    ->AddTitle("Navigation Parser Testing")
    ->Line("A class for better Support Link with this Abilities")
    ->List([
        'Parse And Decoding',
        'Extract Data',
        'Get Parameter',
        'Check Method',
    ])
    ->Line('Current Request:')
    ->List([
        'Use Data Class or Interface',
        'Check Authentication'
    ]);
$TestResult
    ->TitleWithCode("Parse And Load", "")
    ->Table([
        ToArray('Load()', "Load From REQUEST_URI", ""),
        ToArray('Parse(link)', "Parse Link", ""),
    ]);

$TestResult->AddTitle("Default Values", 2);
$Nav->Parse("");
$TestResult
    ->Log("Result", $Nav->ToString())
    ->Log("Controller", $Nav->controller)
    ->Log("Action", $Nav->action)
    ->Log("ID", $Nav->id);

$TestResult->AddTitle("Sample Parsing", 2);

$CurrentLink = "/application/current/domain/?token=1000";
$Nav->Parse($CurrentLink);
$TestResult
    ->Log("Link", $CurrentLink)
    ->Log("Result", $Nav->ToString())
    ->Log("Controller", $Nav->controller)
    ->Log("Action", $Nav->action)
    ->Log("ID", $Nav->id);

$TestResult
    ->TitleWithCode("Checking", $CurrentLink)
    ->Table([
        ToArray('OnController("application")', "Check Controller", json_encode($Nav->OnController("application"))),
        ToArray('OnController("hello")', "Wrong", json_encode($Nav->OnController("hello"))),

        ToArray('OnAction("current")', "Check Action", json_encode($Nav->OnAction("current"))),
        ToArray('OnAction("hello")', "Wrong", json_encode($Nav->OnAction("hello"))),
    ]);

$TestResult
    ->TitleWithCode("Get Parameter", $CurrentLink)
    ->Table([
        ToArray('GetParameter("token")', "Get Parameter From Link", json_encode($Nav->GetParameter("token"))),
    ]);

$TestResult
    ->TitleWithCode("invalid parameter name ", $CurrentLink)
    ->Table([
        ToArray('GetParameter("token-2")', "Wrong Value", json_encode($Nav->GetParameter("token-2"))),
    ]);

$CurrentLink = "/application/current/domain/?=#";
$Nav->Parse($CurrentLink);
$TestResult
    ->TitleWithCode("invalid url ", $CurrentLink)
    ->Table([
        ToArray('GetParameter("token-2")', "Wrong Value", json_encode($Nav->GetParameter("token-2"))),
    ]);

$CurrentLink = "/application/current/domain/";

$Nav->Parse($CurrentLink);
$TestResult
    ->TitleWithCode("empty", $CurrentLink)
    ->Table([
        ToArray('GetParameter("token-2")', "Wrong Value", json_encode($Nav->GetParameter("token-2"))),
    ]);


$TestResult->SaveOutput(__FILE__);


echo $TestResult->ResultAsArticle();;
