<?php
$TestVariable = "normal";
include_once "../data-type/NetString.php";
class TestReportBuilder {
    public string $Result;

    public function __construct() {

        $this->Result = <<<'style'
            <style>
                :root{font-size:20px;}
                code,td {font-size: 1rem;}
                h1,h2,h3,h4,h5,h6 {font-size: 1.2rem;}
                p {margin:0;}
                body{background-color:black; color:white;font-family: lato,serif; }
                h2{font-family: cookierun,serif;font-weight: 100;margin: 20px 0 0 0 ; }
                code{color:#c0de00;}
                pre{margin: 0 5px;}
                table{margin: 0 auto;background-color: #121212;min-width: 800px;}
                th{color:#007fff;text-align:left;font-family: cookierun,serif;font-weight: 100; }
                tag-text { display: flex; gap:10px }
                tag-text b{color:#fff700;font-family: cookierun,serif;font-weight: 100; min-width: 150px;text-align:end }
                tag-text b:after{ content:" : " }
                article { margin: 10px auto;  max-width: 1200px }
                main{display:flex}
                aside{ }
            </style>
            style;

    }

    function SaveOutput(string $string): static {
        file_put_contents($string . "-result.html", $this->Result);
        return $this;
    }


    public function AddTitle(string $Title, $h = 1): static {
        $this->Result .= "<h$h>$Title  </h$h>";
        return $this;
    }

    function TitleWithCode($Title, $code): static {
        $this->Result .= "<h2>$Title <pre style='font-size: 0.7rem;display: inline-flex'>{$code }</pre> </h2>";
        return $this;
    }

    public function Line(string $string): static {
        $this->Result .= "<p>$string</p>";
        return $this;
    }

    public function Log(string $Tag, string $content): static {
        $this->Result .= "<tag-text><b>$Tag</b><code>$content</code></tag-text>";
        return $this;
    }

    public function Table(...$arr): static {
        $this->Result .= "<table>";
        $this->Result .= '<tr><th>Code</th> <th>Description</th> <th>Result</th> </tr>';

        foreach ($arr as $value) {
            if (empty($value[2]) || $value[2] == "") {
                $this->Result .= "<tr><td><pre><code>{$value[0]}</code></pre></td>  <td>{$value[1]}</td></td>";
            } else {
                if (str_ends_with($value[2], " ") || str_starts_with($value[2], " "))
                    $this->Result .= <<<"html"
                    <tr><td><pre><code>{$value[0]}</code></pre></td>  <td>{$value[1]}</td>   <td><pre>=> [<code style="color:red" >{$value[2]}</code>]</pre></td> </tr>
                    html;
                else
                    $this->Result .= <<<"html"
                    <tr><td><pre><code>{$value[0]}</code></pre></td><td>{$value[1]}</td><td><pre><code style="color:red" >{$value[2]}</code></pre></td> </tr>
                    html;
            }
        }

        $this->Result .= "</table>";
        return $this;
    }


    public function List(...$array): static {

        $this->Result .= "<ul>";
        foreach ($array as $value) {
            $this->Result .= "<li>$value</li>";
        }
        $this->Result .= "</ul>";
        return $this;
    }

    public function Add(...$array): static {
        foreach ($array as $value) {
            $css = $value[2] ?? "";
            $this->Result .= "<$value[0] style='$css'>$value[1]</$value[0]>";
        }
        return $this;
    }


    public function ResultAsArticle(): string {
        return "<article> $this->Result </article>";
    }

    public function AsSideContent($sideContent): string {
        return "<main><aside> $sideContent </aside><article>$this->Result</article></main>";
    }

    public function Clear(): static {
        $this->Result = "";
        return $this;
    }

    public function ResultTable(string $json_encode): static {
        $this->Result .= "<table><tr><th>Result</th></tr><tr><td><pre><code>$json_encode</code></pre></td></tr></table>";
        return $this;
    }

    public function TestResultSuccess(string $string): void {
        $this->Add(
            ["b", "Success: ", "color:green"],
            ["code", $string  ],
        );
    }

    public function TestResultFailed(string $string) : void {
        $this->Add(
            ["b", "Failed: ", "color:red"],
            ["code", $string  ],
        );
    }

    public function Section(string $string): TestReportBuilder {
        $sec = new TestReportBuilder();
        $sec->Result = "" ;
        $sec->AddTitle($string);
        return $sec;
    }

    public function Create(...$array): static {
        $css = $array[2] ?? "";
        $name = new NetString($array[0]);
        $start = $name->Replace(" ","><") ;
        $ends = array_reverse($name->Split(" ")) ;
        $end =  $name->Join("></" , $ends);
       // $end = str_replace(" ", "></" , $array[0]);
        $this->Result .= "\n<$start style='$css'>$array[1]</$end>\n";
        return $this;
    }

    public function Report(string $Tag, string $content,string $css=""): static {
        $this->Result .= "<tag-text><b style='width: auto;$css'>$Tag</b><code>$content</code></tag-text>";
        return $this;
    }
}


function ToArray($code, $title, $result): array {
    return [$code, $title, $result];
}