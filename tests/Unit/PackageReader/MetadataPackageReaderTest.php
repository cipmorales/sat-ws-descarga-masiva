<?php

declare(strict_types=1);

namespace PhpCfdi\SatWsDescargaMasiva\Tests\Unit\PackageReader;

use PhpCfdi\SatWsDescargaMasiva\PackageReader\MetadataPackageReader;
use PhpCfdi\SatWsDescargaMasiva\Tests\TestCase;

/**
 * This tests uses the Zip file located at tests/_files/zip/metadata.zip that contains:
 *
 * __MACOSX/ // commonly generated by MacOS when open the file
 * __MACOSX/._45C5C344-DA01-497A-9271-5AA3852EE6AE_01.txt // commonly generated by MacOS when open the file
 * 00000000-0000-0000-0000-000000000000_00.txt // file with correct name but not a metadata file
 * 45C5C344-DA01-497A-9271-5AA3852EE6AE_01.txt // file with metadata 2 rows
 * empty-file // zero bytes file
 * other.txt // file with incorrect extension and incorrect content
 */
class MetadataPackageReaderTest extends TestCase
{
    public function testCountAllContents(): void
    {
        $expectedNumberFiles = 1;
        $expectedNumberRows = 2;

        $filename = $this->filePath('zip/metadata.zip');
        $metadataPackageReader = MetadataPackageReader::createFromFile($filename);

        $this->assertCount($expectedNumberRows, $metadataPackageReader);
        $this->assertCount($expectedNumberFiles, $metadataPackageReader->fileContents());
    }

    public function testRetrieveMetadataContents(): void
    {
        $filename = $this->filePath('zip/metadata.zip');
        $metadataPackageReader = MetadataPackageReader::createFromFile($filename);

        $this->assertCount(2, $metadataPackageReader->metadata());

        $extracted = [];
        foreach ($metadataPackageReader->metadata() as $item) {
            $extracted[] = $item->uuid;
        }

        $expected = [
            'E7215E3B-2DC5-4A40-AB10-C902FF9258DF',
            '129C4D12-1415-4ACE-BE12-34E71C4EAB4E',
        ];
        $this->assertSame($expected, $extracted);
    }
}
