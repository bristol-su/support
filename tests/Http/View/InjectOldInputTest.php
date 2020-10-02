<?php

namespace BristolSU\Support\Tests\Http\View;

use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Laracasts\Utilities\JavaScript\Transformers\Transformer;

class InjectOldInputTest extends TestCase
{

    /** @test */
    public function it_injects_any_old_input_as_an_array(){
        $request = $this->prophesize(Request::class);
        $request->old()->willReturn(['old' => 'input']);

        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(['old_input' => ['old' => 'input']])->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());

        (new \BristolSU\Support\Http\View\InjectOldInput($request->reveal()))->compose(
          $this->prophesize(View::class)->reveal()
        );
    }

    /** @test */
    public function it_injects_an_empty_array_if_no_input(){
        $request = $this->prophesize(Request::class);
        $request->old()->willReturn([]);

        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(['old_input' => []])->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());

        (new \BristolSU\Support\Http\View\InjectOldInput($request->reveal()))->compose(
          $this->prophesize(View::class)->reveal()
        );
    }

}
