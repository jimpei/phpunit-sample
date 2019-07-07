<?

class Bar {

    public function __constructor () {
    }

    public function functionE ($flg) {
        if ($flg) {
            undefinedFunction();
        } else {
            throw new exception('throw exception!!');
        }
        return 'hoge';
    }

}
