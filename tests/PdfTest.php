<?php

namespace Spatie\PdfToImage\Test;

use Spatie\PdfToImage\Pdf;
use PHPUnit\Framework\TestCase;
use Spatie\PdfToImage\Exceptions\InvalidFormat;
use Spatie\PdfToImage\Exceptions\PdfDoesNotExist;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Spatie\PdfToImage\Exceptions\PageDoesNotExist;

class PdfTest extends TestCase
{
    protected static $GS_PATH = null;

    // protected static $GS_PATH = "P:\\gs9.53.3\\bin\\gs.exe";

    /** @var string */
    protected $testFile;

    /** @var string */
    protected $multipageTestFile;

    /** @var \Spatie\TemporaryDirectory\TemporaryDirectory */
    protected $temporaryDirectory;

    public function setUp()
    {
        parent::setUp();

        $this->testFile = __DIR__.'/files/test.pdf';

        $this->multipageTestFile = __DIR__.'/files/multipage-test.pdf';

        $this->temporaryDirectory = new TemporaryDirectory(__DIR__.'/temp');

        $this->temporaryDirectory
            ->force()
            ->empty();
    }

    /** @test */
    public function it_can_convert_a_pdf()
    {
        $image = (new Pdf($this->multipageTestFile, static::$GS_PATH))
            ->saveImage($this->temporaryDirectory->path('image.jpg'));
    }

    /** @test */
    public function it_will_throw_an_exception_when_try_to_convert_a_non_existing_file()
    {
        $this->expectException(PdfDoesNotExist::class);

        new Pdf('pdfdoesnotexists.pdf', static::$GS_PATH);
    }

    /** @test */
    public function it_will_throw_an_exception_when_try_to_convert_to_an_invalid_file_type()
    {
        $this->expectException(InvalidFormat::class);

        (new Pdf($this->testFile, static::$GS_PATH))->setOutputFormat('bla');
    }

    /** @test */
    public function it_will_throw_an_exception_when_passed_an_invalid_page()
    {
        $this->expectException(PageDoesNotExist::class);

        (new Pdf($this->testFile, static::$GS_PATH))->setPage(5);
    }
}
