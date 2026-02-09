<?php

namespace Tests\Feature\app\Http\Controllers\Web\Widget;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WidgetTest extends TestCase
{
    #[Test]
    public function test_get_widget(): void
    {
        $response = $this->get(route('feedback-widget'));

        $response->assertStatus(200);
        $response->assertViewIs('Widget.widget');
    }
}
