<?php


class NavUrl {
    public string $controller;
    public string $action;
    public string $id;
    public string $AbsoluteUrl;

    public function __construct() {
        $this->AbsoluteUrl = "";
        $this->controller = "home";
        $this->action = "index";
        $this->id = "";
    }

    public function Load(): void {
        $this->Parse($_SERVER["REQUEST_URI"]);
    }

    public function Parse(string $url): void {
        $this->AbsoluteUrl = $url;
        $url = explode("?", $url)[0];
        $parts = explode("/", $url);
        $words = [];
        foreach ($parts as $key => $part) {
            if (strlen($part) == 0) continue;
            if (str_ends_with($part, ".php")) continue;
            $words[] = $part;
        }

        if (count($words) == 0) return;
        if ($words[0] == "u") {
            if (count($words) > 1) $this->controller = $words[1];
            if (count($words) > 2) $this->action = $words[2];
            if (count($words) > 3) $this->id = $words[3];
            return;
        }

        $this->controller = $words[0];
        if (count($words) > 1) $this->action = $words[1];
        if (count($words) > 2) $this->id = $words[2];
        return;

    }

    public function OnController(string $con): bool {
        if ($this->controller != $con) return false;
        return true;
    }


    /** Check Requested Action
     * (Optional) Check Method Is Valid
     * */
    public function OnAction(string $pass, string $method = ""): bool {
        if ($this->action != $pass) return false;
        if ($method != "" && $_SERVER['REQUEST_METHOD'] !== $method) return false;
        return true;
    }

    public function ToString(): string {
        return $_SERVER['REQUEST_METHOD'] . " " . $this->controller . "/" . $this->action . "/" . $this->id;
    }

    public function GetParameter(string $strName): array {
        $queryString = parse_url($this->AbsoluteUrl, PHP_URL_QUERY);
        if (empty($queryString)) return [false, "empty"];
        if (strlen($queryString) < 4) return [false, "invalid query string"];
        parse_str($queryString, $params);
        if (isset($params[$strName])) return [true, htmlspecialchars($params[$strName])];
        return [false, "not found"];
    }


}
