<?php


include_once "../data-type/NetString.php";

include_once "lib/TestReportBuilder.php";

$result = new TestReportBuilder();

$result
    ->AddTitle("NetString Class")
    ->Line("A class for better Support string as OOP with this Abilities")
    ->Line('<ul><li>Url Decoding</li><li>Split</li><li>Padding</li><li>Replacing</li></ul>')
    ->Line("Current Request:")
    ->Line('<ul><li>Support Base64</li></ul>')
;

$TestVariable = "This IS Sample TExt";
$str = new NetString($TestVariable);

$str->text = "123 - 456 - 789 - 123 - 456";
$result->TitleWithCode('Indexing', $str->text)->Table([
    ToArray('Get Length', 'Length()', $str->Length()),
    ToArray('Get Index', 'IndexOf("789")', $str->IndexOf("789")),
    ToArray('Get Last Index', 'LastIndexOf("123")', $str->LastIndexOf("123")),
]);

$str->text = $TestVariable;
$result->TitleWithCode("Change Case", $str->text)->Table([
    ToArray('Lower Case', "ToLower()", $str->ToLower()),
    ToArray('Upper Case', "ToUpper()", $str->ToUpper()),
    ToArray("To Capitalize", 'Capitalize()', $str->Capitalize()),
    ToArray("Capitalize Keep Lower", 'Capitalize(true)', $str->Capitalize(true)),
    ToArray("Capitalize First Letter", 'Capitalize(false, true)', $str->Capitalize(false, true)),
    ToArray("Capitalize First And Keep Lower", 'Capitalize(true, true)', $str->Capitalize(true, true)),
]);


$str->text = "0123456789abcdefghijklmnop";
$result->TitleWithCode("Take Part", $str->text)->Table([
    ToArray('SubString', "SubString(5)", $str->SubString(5)),
    ToArray('SubString with Length', "SubString(5, 3)", $str->SubString(5, 3)),
    ToArray('Another Test', "SubString(4, 10)", $str->SubString(4, 10)),
    ToArray("Mixed with IndexOf", 'SubString($str->IndexOf("IS"))', $str->SubString($str->IndexOf("IS"))),
]);
$str->text = $TestVariable;
$result->TitleWithCode("Functions", $str->text)->Table([
    ToArray("TextAfter", 'TextAfter("IS")', $str->TextAfter("IS")),
    ToArray("Use IgnoreCase", 'TextAfter("sa", true)', $str->TextAfter("sa", true)),
    ToArray("Wrong Data", 'TextAfter("www")', $str->TextAfter("www")),
    ToArray("TextBefore", 'TextBefore("IS")', $str->TextBefore("IS")),
    ToArray("Use IgnoreCase", 'TextBefore("sa", true)', $str->TextBefore("sa", true)),
    ToArray("Wrong Data", 'TextBefore("www")', $str->TextBefore("www")),
]);
$str->text = $TestVariable;
$result->TitleWithCode("Check And Sort", $str->text)->Table([
    ToArray("Contain Text", 'Contains(" ")', json_encode($str->Contains(" "))),
    ToArray("Wrong Input", 'Contains("=")', json_encode($str->Contains("="))),
    ToArray("Compare", 'Compare("b" , "a")', json_encode($str->Compare("b", "a"))),
    ToArray("Compare", 'Compare("a" , "a")', json_encode($str->Compare("a", "a"))),
    ToArray("Wrong Input", 'Compare("a" , "b")', json_encode($str->Compare("a", "b"))),
]);

$str->text = $TestVariable;
$result->TitleWithCode("Special Format", $str->text)->Table([
    ToArray("Split Text", 'Split(" ")', json_encode($str->Split(" "))),
    ToArray("Join Text", 'Join(",",[Array])', $str->Join(",", $str->Split(" "))),
]);
$str->text = "link?param=sample data";
$result->TitleWithCode("Url Format", $str->text)->Table([
    ToArray('Url Encode', "UrlEncode()", $str->UrlEncodes()),
    ToArray('Url Decode', "UrlDecode()", $str->UrlDecode()),
]);

$str->text = "11";
$result->TitleWithCode("Padding Format", $str->text)->Table([
    ToArray('Padding Left', 'PadLeft(5, "0")', $str->PadLeft(5, "0")),
    ToArray('Padding Right', 'PadRight(5, "0")', $str->PadRight(5, "0")),
]);

$str->text = "  SPACE  ";
$result->TitleWithCode("Trimming", $str->text)->Table([
    ToArray("Trim", 'Trim()', $str->Trim()),
    ToArray("Trim Start", 'TrimStart()', $str->TrimStart()),
    ToArray("Trim End", 'TrimEnd()', $str->TrimEnd()),
]);

$str->text = "123 - 456 - 789 - 123 - 456";
$result->TitleWithCode("Modify Test", $str->text)->Table([
    ToArray("Replace 123 with AAA", 'Replace("123", "AAA")', $str->Replace("123", "AAA")),
    ToArray("Replace And Change Data ", 'Replaced("123", "AAA")->text', $str->Replaced("123", "AAA")->text),
    ToArray("Use Combination", 'Replaced("456", "BBV")->Replace("BBV", "BBB")', $str->Replaced("456", "BBV")->Replace("BBV", "BBB")),
]);


$result->SaveOutput(__FILE__);


echo $result->ResultAsArticle();