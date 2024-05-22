<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../src/classes/Helpers/CleanStoredTextareaValues.php');

final class CleanStoredTextareaValuesTest extends TestCase 
{
  public function testGetValues() {
      $str = "hitmail.com     rxdoc.biz         cox.com";
      $values = new CleanStoredTextAreaValues($str);
      $this->assertIsString($str);
      $this->assertEquals($values->getValues(), ['hitmail.com', 'rxdoc.biz', 'cox.com']);
  }
}