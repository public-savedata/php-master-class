<?php

// # TEST COMPLETE

class NetString {
    public string $text;

    public function __construct($text) {
        $this->text = $text;
    }

    // # Indexing
    function Length(): int {
        return strlen($this->text);
    }

    function IndexOf($Search): int {
        if (!str_contains($this->text, $Search)) return -1;
        return strpos($this->text, $Search);
    }

    function LastIndexOf($Search): int {
        if (!str_contains($this->text, $Search)) return -1;
        return strrpos($this->text, $Search);
    }


    // # take Part


    function SubString(int $start, int $length = -1): string {
        if ($length > 0) return substr($this->text, $start, $length);
        return substr($this->text, $start);
    }

    function TextAfter($Search, bool $IgnoreCase = false): string {
        $res = $this->text;
        $trg = $Search;
        if ($IgnoreCase) {
            $res = strtolower($res);
            $trg = strtolower($trg);
        }
        if (!str_contains($res, $trg)) return "";
        $var_Poses = strpos($res, $trg) + strlen($trg);
        return substr($res, $var_Poses);
    }

    function TextBefore($Search, bool $IgnoreCase = false): string {
        $res = $this->text;
        $trg = $Search;
        if ($IgnoreCase) {
            $res = strtolower($res);
            $trg = strtolower($trg);
        }
        if (!str_contains($res, $trg)) return $this->text;
        $Start = strpos($res, $trg);
        return substr($res, 0, $Start);
    }

    // # Change Case
    public function ToLower(): string {
        return strtolower($this->text);
    }

    public function ToUpper(): string {
        return strtoupper($this->text);
    }

    public function Capitalize(bool $KeepLower = false, bool $FirstLetterOnly = false): string {
        if ($FirstLetterOnly && $KeepLower) return ucfirst($this->ToLower());
        if ($FirstLetterOnly) return ucfirst($this->text);
        if ($KeepLower) return ucwords($this->ToLower());
        return ucwords($this->text);
    }

// # Modify
    function Replace($txt1, $txt2): string {
        return str_replace($txt1, $txt2, $this->text);
    }

    function Replaced($txt1, $txt2): static {
        $this->text = str_replace($txt1, $txt2, $this->text);
        return $this;
    }

    // >> Triming
    public function Trim(): string {
        return trim($this->text);
    }

    public function TrimStart(): string {
        return ltrim($this->text);
    }

    public function TrimEnd(): string {
        return rtrim($this->text);
    }

    // >> Split And Join
    public function Split(string $string): array {
        return explode($string, $this->text);
    }

    public function Join(string $string, array $Split): string {
        return implode($string, $Split);
    }

    public function Contains(string $string): bool {
        return str_contains($this->text, $string);
    }

    public function Compare(string $string, string $string1): int {
        return strcmp($string, $string1);
    }

    public function UrlEncodes(): string {
        return urlencode($this->text);
    }

    public function UrlDecode(): string {
        return urldecode($this->text);
    }

    public function PadLeft(int $int, string $chr): string {
        return str_pad($this->text, $int, $chr, STR_PAD_LEFT);
    }

    public function PadRight(int $int, string $chr): string {
        return str_pad($this->text, $int, $chr, STR_PAD_RIGHT);
    }

}
