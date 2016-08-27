<?php


namespace GRG;


class Common {

    public static function normalize($data, $types) {
        $types = explode('|', $types);
        if (is_array($types)) {
            foreach ($types as $type) {
                switch ($type) {
                    case 'int':
                        $data = (int) $data;
                        break;
                    case 'double':
                        $data = (double) $data;
                        break;
                    case 'float':
                        $data = (float) $data;
                        break;
                    case 'bool':
                        $data = (bool) $data;
                        break;
                    case 'string':
                        $data = (string) $data;
                        break;
                    case 'trim':
                        $data = trim($data);
                        break;
                    case 'xss':
                        $data = \GRG\Utils\XSSClean::getInstance()->clean_input($data);
                        break;
                }

                if ($type == 'int') {
                    $data = (int) $data;
                }
            }

        }

        return $data;
    }
}