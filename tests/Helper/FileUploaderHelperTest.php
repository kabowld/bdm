<?php

namespace App\Tests\Helper;

use App\Helper\FileUploaderHelper;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderHelperTest extends TestCase
{
    public function testWithExpectedExceptionToUploadFile()
    {
        $slugInterface = $this->getMockBuilder(SluggerInterface::class)->getMock();
        $loggerInterface = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $fileUploader = new FileUploaderHelper($slugInterface, $loggerInterface);

        $originFile = 'loo.png';
        $message = sprintf('Error file to copy : Failed to copy "%s" because file does not exist.', $originFile);

        $loggerInterface
            ->expects($this->once())
            ->method('error')
            ->with($message)
        ;

        $upload = $fileUploader->upload($originFile, 'lo.png');
        $this->assertFalse($upload);
    }

    public function testSuccessUploadFile()
    {
        $slugInterface = $this->getMockBuilder(SluggerInterface::class)->getMock();
        $loggerInterface = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $fileUploader = new FileUploaderHelper($slugInterface, $loggerInterface);

        $originFile = __DIR__.'/Files/logo.png';

        $upload = $fileUploader->upload($originFile,  __DIR__.'/Files/logo'.uniqid().'.png');
        $this->assertTrue($upload);
    }
}
