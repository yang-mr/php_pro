<?php
    class TestTrait
    {
        use MyTrait;
            
        public function test()
        {
            echo $this->getName();
        }
    }

    (new TestTrait())->test();