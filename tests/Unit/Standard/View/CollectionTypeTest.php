<?php

namespace Psi\Component\ContentType\Tests\Unit\Standard\View;

use Psi\Component\ContentType\Field;
use Psi\Component\ContentType\FieldLoader;
use Psi\Component\ContentType\Standard\View\CollectionType;
use Psi\Component\ContentType\Standard\View\CollectionView;
use Psi\Component\ContentType\View\View;
use Psi\Component\ContentType\View\ViewFactory;

class CollectionTypeTest extends TypeTestCase
{
    private $fieldLoader;

    public function setUp()
    {
        $this->fieldLoader = $this->prophesize(FieldLoader::class);
        $this->field = $this->prophesize(Field::class);
        $this->viewFactory = $this->prophesize(ViewFactory::class);
    }

    protected function getType()
    {
        return new CollectionType($this->fieldLoader->reveal());
    }

    /**
     * It should create a collection view from an iterator.
     * It should create a collection view from an array.
     *
     * @dataProvider provideViewIterate
     */
    public function testViewIterate($data)
    {
        $fieldType = 'foo';
        $viewType = 'view_type';
        $fieldOptions = $viewOptions = ['foo' => 'bar'];
        $template = 'some/template';

        $this->fieldLoader->load(
            $fieldType,
            $fieldOptions
        )->willReturn($this->field->reveal());
        $this->field->getViewType()->willReturn($viewType);
        $this->field->getViewOptions()->willReturn($viewOptions);

        $view = $this->getType()->createView(
            $this->viewFactory->reveal(),
            $data,
            [
                'template' => $template,
                'field_type' => $fieldType,
                'field_options' => $fieldOptions,
            ]
        );

        $this->assertInstanceOf(CollectionView::class, $view);
        $this->assertEquals($template, $view->getTemplate());
    }

    public function provideViewIterate()
    {
        return [
            [
                [
                    new \stdClass(),
                    new \stdClass(),
                    new \stdClass(),
                ],
            ],
            [
                new \ArrayObject([
                    new \stdClass(),
                    new \stdClass(),
                    new \stdClass(),
                ]),
            ],
        ];
    }

    /**
     * It should throw an exception if a non-traversale or non-array was passed as the value.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Data must be traversable or an array, got: "string"
     */
    public function testNonTraversable()
    {
        $data = 'hello';
        $this->getType()->createView(
            $this->viewFactory->reveal(),
            $data,
            [
            ]
        );
    }
}
