<?php


class JSON {
    static function Parse(string $content) {
        return json_decode($content, true);
    }
    // static function LoadFromData(string $shortPath)
    // {
    //     $fullpath = "data/" . $shortPath . ".json";
    //     if (IOFile::Exists($fullpath)) {
    //         $content = file_get_contents($fullpath);
    //         return json_decode($content, true);
    //     }
    //     return [];
    // }
    static function LoadFromTemplate(string $Name): mixed {
        $content = "{}";
        $link = "api/model/$Name.json";
        if (file_exists($link)) $content = file_get_contents($link);
        return json_decode($content, true);
    }

    // static function SaveData(string $Path, $Content)
    // {

    //     $fullpath = "data/" . $Path . ".json";
    //     $File = new FileInfo($fullpath);

    //     // >>  Saving
    //     $File->GetDirectory()->Create();
    //     $File->WriteAllText($Content);

    // }
}
