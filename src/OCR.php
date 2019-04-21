<?php

class OCR {
    const ACCOUNT_NUMBER_DIGIT_WIDTH = 3;
    const SPLIT_BY_LINE_REGEX = "#((\r?\n)|(\r\n?).*)#";
    const ACCOUNT_NUMBER_DIGIT_MAP = Array(
        <<<'EOD'
           
          |
          |
        EOD => "1" ,
        <<<'EOD'
         _ 
         _|
        |_ 
        EOD => "2" ,
        <<<'EOD'
         _ 
         _|
         _|
        EOD => "3" ,
        <<<'EOD'
           
        |_|
          |
        EOD => "4" ,
        <<<'EOD'
         _ 
        |_ 
         _|
        EOD => "5" ,
        <<<'EOD'
         _ 
        |_ 
        |_|
        EOD => "6" ,
        <<<'EOD'
         _ 
          |
          |
        EOD => "7" ,
        <<<'EOD'
         _ 
        |_|
        |_|
        EOD => "8" ,
        <<<'EOD'
         _ 
        |_|
         _|
        EOD => "9"
    );

    public function getAccountNumberArray($input) {
        $account_number_line_array = [];

        foreach($this->groupInputEvery4Lines($input) as $individual_account_number_line_array){
            array_pop($individual_account_number_line_array);
            $account_number_line_array[] = implode("\r\n", $individual_account_number_line_array);
        }
        return $account_number_line_array;
    }

    private function groupInputEvery4Lines($input): array {
        return array_chunk(preg_split(self::SPLIT_BY_LINE_REGEX, $input), 4);
    }

    public function convertAccountNumberIntoIndividualDigits(string $account_number_string) {
        $account_number_string_lines = explode("\r\n", $account_number_string);
        $account_number_digit_2d_array = $this->groupEachLineByAccountNumberDigitWidth($account_number_string_lines);

        return $this->createAnArrayContainingEachAccountNumberDigit($account_number_digit_2d_array);
    }

    private function groupEachLineByAccountNumberDigitWidth(array $account_number_string_lines) {
        return array_map(function ($line) {
            return str_split($line, self::ACCOUNT_NUMBER_DIGIT_WIDTH);
        }, $account_number_string_lines);
    }

    private function createAnArrayContainingEachAccountNumberDigit(array $account_number_digit_2d_array) {
        $account_number_digit_2d_array = $this->transpose($account_number_digit_2d_array);

        return array_map(function ($digit) {
            return implode("\r\n", $digit);
        }, $account_number_digit_2d_array);
    }

    private function transpose(array $array) :array {
        return array_map(null, ...$array);
    }

    public function get_account_number(array $account_number_individual_digits) {
        $ocr_individual_digit_array = array_map(function ($account_number_digit) {
            return self::ACCOUNT_NUMBER_DIGIT_MAP[$account_number_digit];
        }, $account_number_individual_digits);

        return implode($ocr_individual_digit_array);
    }
}
