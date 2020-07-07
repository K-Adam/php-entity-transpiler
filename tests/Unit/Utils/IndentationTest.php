<?php


namespace Tests\Unit\Utils;

use Tests\TestCase;
use EntityTranspiler\Utils\Indentation;

class IndentationTest extends TestCase {
    
    public function testSpace() {
        
        $id = new Indentation(Indentation::TYPE_SPACE, 2);
        $this->assertEquals("    ", $id->get(2));
        
    }
    
    public function testTab() {
        
        $id = new Indentation(Indentation::TYPE_TAB, 1);
        $this->assertEquals("\t\t\t", $id->get(3));
        
    }
    
    public function testCreate() {
        
        $type = Indentation::TYPE_SPACE;
        $count = 2;
        
        $id = Indentation::create([
            "type" => $type,
            "count" => $count
        ]);
        
        $this->assertEquals($type, $id->getType());
        $this->assertEquals($count, $id->getCount());
        
    }
    
}
