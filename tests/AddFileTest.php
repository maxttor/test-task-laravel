<?php

use App\File;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddFileTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Storage::delete(Storage::files());
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAddValidUrl()
    {
        $exception = null;
        $file = null;

        try {
            $file = File::create(['url' => 'https://smartprogress.do/images/logo.jpg'], false);
        } catch (Throwable $exception) {}
        $this->assertNull($exception);
        $this->assertNotEmpty($file);
        $this->assertEquals($file->status,File::STATUS_PENDING);

        $exception = null;
        try {
            $this->assertTrue($file->download());
        } catch (Throwable $exception) {}
        $this->assertNull($exception);
        $this->assertEquals($file->status,File::STATUS_COMPLETE);
    }

    public function testAddInvalidUrl()
    {
        $exception = null;
        $file = null;

        try {
            $file = File::create(['url' => 'http://example.loc/logo.jpg'], false);
        } catch (Throwable $exception) {}
        $this->assertNull($exception);
        $this->assertNotEmpty($file);

        $exception = null;
        try {
            $this->assertFalse($file->download());
        } catch (Throwable $exception) {}
        $this->assertNotEmpty($exception);
        $this->assertEquals($file->status,File::STATUS_ERROR);
    }
}
