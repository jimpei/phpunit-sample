<?
// for functionG
require_once("Bar.php");

class Foo {

    // public $Bar;

    public function __constructor () {
    }

    public function functionA () {
        return 'hoge';
    }

    public function functionB () {
        return 'fuga';
    }

    public function functionC ($flg) {

        if ($flg) {
            undefinedFunction();
        } else {
            throw new exception('throw exception!!');
        }
        return 'hoge';
    }

    public function functionD ($flg) {
        return $this->functionC($flg);
    }

    private function functionE ($flg) {
        if ($flg) {
            undefinedFunction();
        } else {
            throw new exception('throw exception!!');
        }
        return 'hoge';
    }

    public function functionF ($flg) {
        return $this->functionE($flg);
    }

    public function functionG (Bar $Bar, $flg) {
        return $Bar->functionE($flg);
    }

    protected function functionH ($flg) {
        if ($flg) {
            undefinedFunction();
        } else {
            throw new exception('throw exception!!');
        }
        return 'hoge';
    }

    public function functionI ($flg) {
        return $this->functionH($flg);
    }

}
