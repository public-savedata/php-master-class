<?php



/** Json Data List */
class JDataList extends QDataList {

    public string $FilePath;


    public function __construct(string $filename) {
        parent::__construct();
        $this->FilePath = $filename;
    }

    public function Load(): void {
        if (file_exists($this->FilePath)) {
            $content = file_get_contents($this->FilePath);
            $this->data= json_decode($content, true);
            return;
        }
        $this->data= [];
    }

    public function Register(array $NewData, bool $SetUid = false): int {
        $nr = [];
        $nr["id"] = 1;
        $nr["data"] = $NewData;

        if ($SetUid) $nr["data"]["uid"] = $this->NextGuid();

        // >> Can Move to Logs
        $nr["create"] = time();
        if (!empty($this->data)) {
            $nr["id"] = end($this->data)["id"] + 1;
        }

        $this->data[] = $nr;
        return $nr["id"];
    }

    public function Update(int $id, string $key, string $value): bool {
        $index = $this->SearchById($id);
        if ($index < 0) return false;
        $this->data[$index]["data"][$key] = $value;
        return true;
    }

    public function UpdateQrProperty(int $id, string $PropertyName, $NewValue): bool {
        $index = $this->SearchById($id);
        if ($index < 0) return false;

        // >> Modify Data
        if (!str_contains($PropertyName, ".")) {
            $this->data[$index]["data"][$PropertyName] = $NewValue;
            return true;
        }
        $level = explode(".", $PropertyName);
        if (sizeof($level) == 2) $this->data[$index]["data"][$level[0]][$level[1]] = $NewValue;
        if (sizeof($level) == 3) $this->data[$index]["data"][$level[0]][$level[1]][$level[2]] = $NewValue;
        if (sizeof($level) == 4) $this->data[$index]["data"][$level[0]][$level[1]][$level[2]][$level[3]] = $NewValue;
        if (sizeof($level) == 5) $this->data[$index]["data"][$level[0]][$level[1]][$level[2]][$level[3]][$level[4]] = $NewValue;
        return true;
    }


    public function SearchById($id): int {
        foreach ($this->data as $index => $item) {
            if ($item['id'] == $id) {
                return $index;
            }
        }
        return -1;
    }


    function SearchByUid(string $uid): int {
        foreach ($this->data as $key => $task) {
            if ($task["data"]["uid"] == $uid) return $key;
        }
        return -1;
    }

    public function Delete(int $id): bool {
        $index = $this->SearchById($id);
        if ($index < 0) return false;
        array_splice($this->data, $index, 1);
        // unset($this->data[$index]);
        return true;
    }

    public function Save() {
        $directory = dirname($this->FilePath);
        if (!is_dir($directory)) {
            // Create the directory if it doesn't exist
            mkdir($directory, 0755, true); // 0755 is the permission
        }


        $jsonString = json_encode($this->data, JSON_PRETTY_PRINT);
        file_put_contents($this->FilePath, $jsonString);

    }

    public function GetAttribute(string $format) {

        echo "\n" . json_encode($this->data);

        $current = $this->data;

        $keys = explode('.', $format);
        foreach ($keys as $key) {
            if (str_ends_with($key, "]")) {
                $search = Extraction($key);
                if ($search[0]) {
                    $current = &$current[$search[1]][$search[2]];
                    continue;
                } else {
                    echo "\n >>>> WRONG";
                    return null;
                }
            }

            if (str_starts_with($key, "{")) {
                $data = explode(":", trim($key, '{}'), 2);

                $r = $this->SearchArrayByMatchingProperties($current, $data[0], $data[1]);

                if ($r >= 0) {
                    $current = &$current[$r];
                    echo "\n ##  Continue  $data[0] : $data[1]";
                    continue;
                } else {
                    echo "\n ##  UNABLE TO FIND  $data[0] : $data[1]";
                    echo json_encode($current);
                    return null;
                }
            }

            $current = &$current[$key];
        }
        return $current;
    }

    function SearchArrayByMatchingProperties(mixed $current, string $int, string $int1) {
        foreach ($current as $index => $item) {
            if ($item[$int] == $int1) {
                return $index;
            }
        }
        return -1;
    }


    function NextGuid(): string {
        return sprintf(
            '%04x-%04x-%04x-%04x',
            random_int(0, 0xffff), // 4 hex digits
            random_int(0, 0xffff), // 4 hex digits
            random_int(0, 0xffff), // 4 hex digits
            random_int(0, 0xffff)  // 4 hex digits
        );
    }
}