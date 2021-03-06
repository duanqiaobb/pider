<?php
define('PIDER_PATH',dirname(__DIR__));
define('APP_ROOT',dirname(__DIR__,2));

include(dirname(__FILE__,2).'/Pider.php');

use PHPUnit\Framework\TestCase;
use Pider\Support\URLCenter;

class URLCenterUnitTest extends TestCase{
    private $uc;

    public function setUp() {
        $this->uc = new URLCenter();
    }

    public function testCanputOne() {
        $url = [
                'http://item.jd.com/1192891.html',
                'http://item.jd.com/1192892.html',
                'http://item.jd.com/1192893.html'
            ];
        foreach($url as $u) {
            $this->uc->putOne($u);
        }
    }

    public function testCangetOne() {
        var_dump($this->uc->getOne());
    }
}
