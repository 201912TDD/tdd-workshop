<?php

use App\Accounting;
use App\IBudgetRepo;
use PHPUnit\Framework\TestCase;

class TestAccounting extends TestCase
{
    private $accounting;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $mock = \Mockery::mock(IBudgetRepo::class);

        $mock->shouldReceive('getAll')
        ->andReturn($this->fakeData());
        $this->accounting = new Accounting($mock);
    }

    public function testQueryOneMonth()
    {
        $startDate = new DateTime('20180401');
        $endDate = new DateTime('20180430');
        $result = $this->accounting->queryBudget($startDate, $endDate);

        $this->assertEquals(30, $result);
    }

    public function testQueryDays()
    {
        $startDate = new DateTime('20180401');
        $endDate = new DateTime('20180415');
        $result = $this->accounting->queryBudget($startDate, $endDate);

        $this->assertEquals(15, $result);
    }

    public function testQueryOverMonth()
    {
        $startDate = new DateTime('20180428');
        $endDate = new DateTime('20180501');
        $result = $this->accounting->queryBudget($startDate, $endDate);

        $this->assertEquals(13, $result);
    }

    public function testQueryOverYear()
    {
        $startDate = new DateTime('20191201');
        $endDate = new DateTime('20201231');
        $result = $this->accounting->queryBudget($startDate, $endDate);

        $this->assertEquals(3782, $result);
    }

    public function testQueryNoBudget()
    {
        $startDate = new DateTime('20191001');
        $endDate = new DateTime('20191031');
        $result = $this->accounting->queryBudget($startDate, $endDate);

        $this->assertEquals(0, $result);
    }

    public function testQueryOver3Months()
    {
        $startDate = new DateTime('20180330');
        $endDate = new DateTime('20180503');
        $result = $this->accounting->queryBudget($startDate, $endDate);

        $this->assertEquals(66, $result);
    }

    public function testQueryWrongTime()
    {
        $startDate = new DateTime('20190330');
        $endDate = new DateTime('20180503');
        $result = $this->accounting->queryBudget($startDate, $endDate);

        $this->assertEquals(0, $result);
    }

    private function fakeData()
    {
        $arr = [
            '201803' => 93,
            '201804' => 30,
            '201805' => 310,
            '201912' => 3100,
            '202001' => 62,
            '202012' => 620,
        ];
        $result = [];
        foreach ($arr as $yearMonth => $amount) {
            $result[] = new \App\Budget($yearMonth, $amount);
        }

        return $result;
    }
}
