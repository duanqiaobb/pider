<?php
namespace UnitTest;

class TestWork extends \Threaded {
    protected $complete;
    //$pData is the data sent to your workder thread to do it's job.
    public function __construct($pData) {
        //transfer all the variables to local variables
        $this->complete = false;
        $this->testData = $pData;
    }

    //This is where all of your work will be done.
    public function run() {
        usleep(2000000); //sleep 2 seconds to simulate a large job
        $this->complete = true;
    }

    public function isGarbage(): bool {
        return $this->complete;
    }
}

class ExamplePool extends \Pool {

    public $data = array();
    public function process() 
    {
        //Run this loop as long as we have
        //jobs in the pool
        while(count($this->work)) {
            $this->collect(function(TestWork $task){
                //If a task was marked as done
                //collect its result
                if ($task->isGarbage()) {
                    $tmpObj = new stdclass();
                    $tmpObj->complete = $task->complete;
                    //this is how you get your completed data back out [accessed by $pool->process()] 
                    $this->data[] = $tmpObj;
                }
                return $task->isGarbage();

            });

            //All jobs are done
            //we can shutdown the pool
            $this->shutdown();
            return $this->data;
        }
    }
}

$pool = new ExamplePool(3);
$testData = 'asdf';

for($i=0; $i < 5; $i++) {
    $pool->submit(new TestWork($testData));
}

$retArr = $pool->process(); //get all of the results
echo '<pre>';
print_r($retArr); //return the array of results (and maybe errors)
echo '</pre>';


