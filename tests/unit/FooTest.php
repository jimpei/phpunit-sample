<?
use PHPUnit\Framework\TestCase;

require_once("Foo.php");
require_once("Bar.php");

class FooTest extends TestCase{

    public function test_mockを使わない () {
        $target = new Foo();

        $result = $target->functionA();
        $this->assertSame('hoge', $result);

        $result = $target->functionB();
        $this->assertSame('fuga', $result);
    }

    public function test_mockを使う () {
        $mock = Phake::mock('Foo');

        // 振る舞いを定義していない関数は、nullになる
        $result = $mock->functionA();
        $this->assertSame(null, $result);
        $result = $mock->functionB();
        $this->assertSame(null, $result);

        // 振る舞いを定義した関数は、スタブ化できる
        Phake::when($mock)->functionA->thenReturn('HOGE');
        $result = $mock->functionA();
        $this->assertSame('HOGE', $result);
        $result = $mock->functionB();
        $this->assertSame(null, $result);

    }

    public function test_pertialMockを使う () {
        $pmock = Phake::partialMock('Foo');

        // 振る舞いを定義していない関数は、元の関数の振る舞いをする
        $result = $pmock->functionA();
        $this->assertSame('hoge', $result);
        $result = $pmock->functionB();
        $this->assertSame('fuga', $result);

        // 振る舞いを定義した関数は、スタブ化できる
        Phake::when($pmock)->functionA->thenReturn('HOGE');
        $result = $pmock->functionA();
        $this->assertSame('HOGE', $result);
        $result = $pmock->functionB();
        $this->assertSame('fuga', $result);
    }

    public function test_public_methodのスタブを直接呼ぶ () {
        $pmock = Phake::partialMock('Foo');

        Phake::when($pmock)->functionC(Phake::anyParameters())->thenReturn('HOGE');

        // 未定義のファンクションエラーもexceptionも発生しない
        $result = $pmock->functionC(true);
        $this->assertSame('HOGE', $result);

        $result = $pmock->functionC(false);
        $this->assertSame('HOGE', $result);
    }

    public function test_public_methodのスタブを間接的に呼ぶ () {
        $pmock = Phake::partialMock('Foo');

        Phake::when($pmock)->functionC(Phake::anyParameters())->thenReturn('HOGE');

        // 未定義のファンクションエラーもexceptionも発生しない
        $result = $pmock->functionD(true);
        $this->assertSame('HOGE', $result);

        $result = $pmock->functionD(false);
        $this->assertSame('HOGE', $result);

    }

    public function test_private_methodのスタブを直接呼ぶ () {
        $pmock = Phake::partialMock('Foo');

        // private methodをmockにする方法はpublicと同じ
        Phake::when($pmock)->functionE(Phake::anyParameters())->thenReturn('HOGE');

        // ただし、publicと同じように呼ぶことはできない
        // $result = $pmock->functionE(true);

        // makeVisibleを使えばprivateメソットも実行できる
        // 未定義のファンクションエラーもexceptionも発生しない
        $result = Phake::makeVisible($pmock)->functionE(true);
        $this->assertSame('HOGE', $result);

        $result = Phake::makeVisible($pmock)->functionE(false);
        $this->assertSame('HOGE', $result);
    }

    public function test_private_methodのスタブを関節的に呼ぶ () {
        $pmock = Phake::partialMock('Foo');

        // private methodをmockにする方法はpublicと同じ
        Phake::when($pmock)->functionE(Phake::anyParameters())->thenReturn('HOGE');

        // 未定義のファンクションエラーが発生する(スタブ化できてない)
        //   Error: Call to undefined function undefinedFunction()
        // $result = Phake::makeVisible($pmock)->functionF(true);
        // $this->assertSame('HOGE', $result);

        // exceptionが発生する(スタブ化できてない)
        try {
            $result = Phake::makeVisible($pmock)->functionF(false);
        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        $this->assertSame('throw exception!!', $result);
    }

    public function test_解決策1：別クラスに押し込み、publicにしてしまう () {
        $barPmock = Phake::partialMock('Bar');
        $fooPmock = Phake::partialMock('Foo');

        Phake::when($barPmock)->functionE(Phake::anyParameters())->thenReturn('HOGE');

        // スタブ化したpublicメソッドを関節的に読んでいるので振る舞いどおりに動く
        $result = $fooPmock->functionG($barPmock, false);
        $this->assertSame('HOGE', $result);
    }

    public function test_protected_methodのスタブを直接呼ぶ () {
        $pmock = Phake::partialMock('Foo');

        // private methodをmockにする方法はpublicと同じ
        Phake::when($pmock)->functionH(Phake::anyParameters())->thenReturn('HOGE');

        // ただし、publicと同じように呼ぶことはできない
        // $result = $pmock->functionH(true);

        // makeVisibleを使えばprotectedメソットも実行できる
        // 未定義のファンクションエラーもexceptionも発生しない
        $result = Phake::makeVisible($pmock)->functionH(true);
        $this->assertSame('HOGE', $result);

        $result = Phake::makeVisible($pmock)->functionH(false);
        $this->assertSame('HOGE', $result);
    }

    public function test_protected_methodのスタブを関節的に呼ぶ () {
        $pmock = Phake::partialMock('Foo');

        // private methodをmockにする方法はpublicと同じ
        Phake::when($pmock)->functionH(Phake::anyParameters())->thenReturn('HOGE');

        // 未定義のファンクションエラーもexceptionも発生しない
        $result = $pmock->functionI(true);
        $this->assertSame('HOGE', $result);

        $result = $pmock->functionI(false);
        $this->assertSame('HOGE', $result);
    }
}
