<?php

class OCRTest extends \PHPUnit\Framework\TestCase {
    private $ocr;

    public function __construct() {
        parent::__construct();
        $this->ocr = new OCR;
    }

    protected function setUp(): void {
        parent::setUp();
        $this->ocr = new OCR;
    }

    public function testGetAccountNumberArray() {
        $input = <<<'EOD'
        1
        2
        3
        
        5
        6
        7
        
        EOD;

        $expected = Array(
            <<<'EOD'
            1
            2
            3
            EOD,

            <<<'EOD'
            5
            6
            7
            EOD
        );

        $this->assertEquals($expected, $this->ocr->getAccountNumberArray($input));
    }

    public function testConvertAccountNumberIntoIndividualDigits(){
        $input = <<<'EOD'
            _  _     _  _  _  _  _ 
          | _| _||_||_ |_   ||_||_|
          ||_  _|  | _||_|  ||_| _|
        EOD;

        $expected = Array(
        <<<'EOD'
           
          |
          |
        EOD,<<<'EOD'
         _ 
         _|
        |_ 
        EOD,<<<'EOD'
         _ 
         _|
         _|
        EOD,<<<'EOD'
           
        |_|
          |
        EOD,<<<'EOD'
         _ 
        |_ 
         _|
        EOD,<<<'EOD'
         _ 
        |_ 
        |_|
        EOD,<<<'EOD'
         _ 
          |
          |
        EOD,<<<'EOD'
         _ 
        |_|
        |_|
        EOD,<<<'EOD'
         _ 
        |_|
         _|
        EOD
        );

        $this->assertEquals($expected, $this->ocr->convertAccountNumberIntoIndividualDigits($input));
    }

    public function testGetAccountNumber(){
        $input = <<<'EOD'
            _  _     _  _  _  _  _ 
          | _| _||_||_ |_   ||_||_|
          ||_  _|  | _||_|  ||_| _|
        EOD;

        $expected = "123456789";

        $account_number_individual_digits = $this->ocr->convertAccountNumberIntoIndividualDigits($input);
        $this->assertEquals($expected, $this->ocr->get_account_number($account_number_individual_digits));
    }
}