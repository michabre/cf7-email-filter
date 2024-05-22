<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../src/classes/Helpers/BuildList.php');

final class BuildListTest extends TestCase 
{
  public function testBuildListFromString() {
      $str = "hitmail.com rxdoc.biz cox.com";
      $this->assertIsString($str);
      $values = new BuildList($str);
      $this->assertEquals($values->buildList(), 'cox.com&#13;&#10;hitmail.com&#13;&#10;rxdoc.biz&#13;&#10;');
  }

  public function testBuildListFromArray() {
    $str = ["hitmail.com", "rxdoc.biz", "cox.com"];
    $this->assertIsArray($str);
    $values = new BuildList($str);
    $this->assertEquals($values->buildList(), 'cox.com&#13;&#10;hitmail.com&#13;&#10;rxdoc.biz&#13;&#10;');
  }
}