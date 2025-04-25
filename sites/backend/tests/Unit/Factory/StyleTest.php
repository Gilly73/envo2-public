<?php

namespace Tests\Unit\Factory;

use Tests\TestCase;
use App\Factory\Style;
use App\Models\Couch\Style as StyleModel;
use App\Exceptions\CouchException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StyleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_style_instantiation_successful()
    {
        // Arrange:
        // Create a record in the couch styles table without explicitly setting the id.
        $record = StyleModel::factory()->create([
            'couch_type_id' => 2,
            'name'          => 'modern indoor comfort',
            'base_price'    => 150.50,
        ]);

        // Act:
        // Instantiate our Style class using the auto-assigned id and the expected couch_type_id.
        $style = new Style($record->id, 2);

        // Assert:
        // Verify that the cost equals the base_price.
        $this->assertEquals(150.50, $style->getCost());
        // The BaseCouchComponent's getDescription() returns:
        // class_basename(static::class) . ' = ' . ucfirst($this->name)
        // Expected: "Style = Modern indoor comfort"
        $this->assertEquals('Style = Modern indoor comfort', $style->getDescription());
    }

    public function test_style_instantiation_not_found_throws_exception()
    {
        // Expect a CouchException when no matching record is found.
        $this->expectException(CouchException::class);
        $this->expectExceptionMessage('Style not found : 999');

        // Act: Try to create a Style with a non-existent id.
        new Style(999, 2);
    }
}
