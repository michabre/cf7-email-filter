<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../src/classes/Helpers/CleanStoredTextAreaValues.php');

final class CleanStoredTextAreaValuesTest extends TestCase 
{
  public function testGetValues() {
      $str = "hitmail.com     rxdoc.biz         cox.com";
      $values = new CleanStoredTextAreaValues($str);
      $this->assertEquals($values->getValues(), ['hitmail.com', 'rxdoc.biz', 'cox.com']);
  }
}