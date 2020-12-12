<?php

namespace BristolSU\Support\Tests\Http\View;

use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Laracasts\Utilities\JavaScript\Transformers\Transformer;

class InjectValidationErrorsTest extends TestCase
{

    /** @test */
    public function it_injects_any_errors_as_an_array(){
        $messageBag = new MessageBag(['test' => ['one' => 'message'], 'another' => ['hi' => 'message', 'bye' => 'message2']]);
        $errorBag = (new ViewErrorBag())->put('default', $messageBag);

        $view = $this->prophesize(View::class);
        $view->getData()->willReturn(['errors' => $errorBag, '_env' => 'test']);

        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(['server_validation_errors' => [
          'test' => ['one' => 'message'], 'another' => ['hi' => 'message', 'bye' => 'message2']
        ]])->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());

        (new \BristolSU\Support\Http\View\InjectValidationErrors())->compose($view->reveal());
    }

    /** @test */
    public function it_injects_an_empty_array_if_no_errors_in_view(){
        $view = $this->prophesize(View::class);
        $view->getData()->willReturn(['_env' => 'test']);

        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(['server_validation_errors' => []])->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());

        (new \BristolSU\Support\Http\View\InjectValidationErrors())->compose($view->reveal());
    }

}
