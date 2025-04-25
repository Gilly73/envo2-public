<?php

namespace Tests\Unit\Models;

use App\Models\Couch\CouchType;
use Tests\TestCase;
use App\Models\Couch\Style;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class CouchStyleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Style::truncate();
        // CouchType::truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // DB::table('couch_types')->insert([
        //     ['id' => 1, 'name' => 'indoor', 'created_at' => now(), 'updated_at' => now()],
        //     ['id' => 2, 'name' => 'outdoor', 'created_at' => now(), 'updated_at' => now()],
        // ]);
    }

    /** @test */
    public function it_allows_mass_assignment_for_fillable_fields()
    {
        $data = [
            'name' => 'Modern',
            'base_price' => 499.99,
            'couch_type_id' => 1,
        ];

        $style = Style::create($data);

        $this->assertInstanceOf(Style::class, $style);
        $this->assertEquals('Modern', $style->name);
        $this->assertEquals(499.99, $style->base_price);
        $this->assertEquals(1, $style->couch_type_id);
    }

    /** @test */
    public function it_can_be_created_using_factory()
    {
        $style = Style::factory()->create([
            'couch_type_id' => 1,
        ]);

        $this->assertInstanceOf(Style::class, $style);
        $this->assertNotNull($style->id);
    }

    /** @test */
    public function it_can_get_styles_with_specific_couch_type_id()
    {
        Style::factory()->count(2)->create(['couch_type_id' => 1]);
        Style::factory()->create(['couch_type_id' => 2]);

        $styles = Style::where('couch_type_id', 1)->get();

        //its 4 because of the seed data
        $this->assertCount(4, $styles);
        $this->assertTrue($styles->every(fn($style) => $style->couch_type_id === 1));
    }

    /** @test */
    public function it_can_get_styles_with_specific_couch_type_id_and_style_id()
    {
        $data = [[
            'id'=> 5,
            'name' => 'Modern',
            'base_price' => 499.99,
            'couch_type_id' => 2,
        ], [
            'id'=> 6,
            'name' => 'Classic',
            'base_price' => 600.99,
            'couch_type_id' => 1,
        ]];
        $style = Style::insert($data);
        //its 5 because of the seed data 4 
        $style = Style::where('id', 5)->get();
        $this->assertCount(1, $style);
        $this->assertEquals(2, $style->first()->couch_type_id);
        $this->assertEquals(5, $style->first()->id);
    }

    /** @test */
    public function it_can_delete_a_style()
    {
        $style = Style::factory()->create();

        $style->delete();

        $this->assertDatabaseMissing('styles', ['id' => $style->id]);
    }
}
