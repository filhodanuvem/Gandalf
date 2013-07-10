<?php

namespace Gandalf\Tests\Entity;

use Gandalf\Tests\Fixtures\Foo;
use Gandalf\Helper;

class CallerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function should_create_method_with_simple_name()
	{
		$foo = new Foo;
		$foo->def('bar', function(){
			return 'fooBar';
		});

		$this->assertEquals($foo->bar(), 'fooBar');
	}

	/**
	 * @test 
	 */ 
	public function should_create_method_with_intuive_way()
	{
		$foo = new Foo;
		$foo->bar = function () {
			return 'Sr. Bilbo';
		};

		$this->assertEquals($foo->bar(), 'Sr. Bilbo');
	}
	
	/**
	 * @test
	 */ 
	public function should_accept_any_callable()
	{
		$foo = new Foo;
		$foo->first_char_to_upper = 'ucfirst';
		$this->assertEquals($foo->first_char_to_upper('foo'), 'Foo');

		$foo->strip = 'trim';
		$this->assertEquals($foo->strip(' This is my name, Galdalf      '), 'This is my name, Galdalf');
	}

	/**
	 * @test
	 * @expectedException \BadMethodCallException
	 */ 
	public function test_if_emit_error_calling_method_not_found()
	{
		$foo = new Foo;
		$foo->bar = 'print';

		$foo->foo();
	}

	/**
	 * @test
	 */ 
	public function should_call_methods_with_simple_pattern_name()
	{
		$foo = new Foo;
		$foo->def('findBy[A-Z][a-z]+', function($pivot){
			return 'found!';
		});

		$this->assertEquals($foo->findByName('Bilbo'), 'found!');
	}

	/**
	 * @test
	 */
	public function should_use_magic_vars_groups()
	{
		$foo = new Foo;
		$foo->def('findBy([A-Z][a-z]+)', function($pivot) {
			return $this->_1;
		});

		$this->assertEquals('Name', $foo->findByName('Bilbo'));
	}

	/**
	 * @test
	 **/ 
	public function should_find_all_vars_groups()
	{
		$foo = new Foo;
		$foo->def('find(One|Two){0,1}By([A-Z][a-z]+)',function () {
			return array($this->_1, $this->_2);
		});

		$this->assertEquals(array('One','Name'), $foo->findOneByName());
		$this->assertEquals(array('Two','Name'), $foo->findTwoByName());
		$this->assertEquals(array(null, 'Name'), $foo->findByName());
	}

	/**
	* @test
	*/
	public function should_generate_all_matches()
	{
		$foo = new Foo;
		$foo->def('find(One|Two){0,1}By([A-Z][a-z]+)',function () {
			return $this->matches;
		});

		$this->assertEquals(array('findOneByName','One','Name'), $foo->findOneByName());
		$this->assertEquals(array('findTwoByName','Two','Name'), $foo->findTwoByName());
		$this->assertEquals(array('findByName',null, 'Name'), $foo->findByName());	
	}

    /**
    * @test
    */
	public function should_crete_simple_shortcut()
	{
		$foo = new Foo;
		$foo->short('sanitize', [
			['trim', ":param1"],
	        ['ucfirst', ":return1"],
		]);

		$this->assertEquals('Go destroy the ring, Bilbo!', $foo->sanitize(' go destroy the ring, Bilbo!    '));
	}

	/**
	* @test
	*/
	public function should_crete_compound_shortcut()
	{
		$foo = new Foo;
		$foo->short('getSlug', [
			['trim', ":param1"],
	        ['strtolower', ":return1"],
	        ['str_replace',' ', '-',":return2"],
		]);

		$this->assertEquals('my-precioussss', $foo->getSlug('My preCiouSsSs'));
		$this->assertEquals('smoug-is-a-danger-dragon', $foo->getSlug(' Smoug is a Danger dragon '));
	}

	/**
	* @test
	* @expectedException \InvalidArgumentException
	*/ 
	public function test_if_emit_error_with_array_without_name()
	{
		$foo = new Foo;
		$foo->short('bar', [
			['trim', ':param1'],
			[]
		]);

		$foo->bar(' foo ');
	}
    
    /**
    * @test
    * @expectedException \BadFunctionCallException
    */ 
	public function test_if_emit_error_calling_method_not_found_in_shortcut()
	{
		do {
			$invalidFunction = uniqid();
		} while (function_exists($invalidFunction));

		$foo = new Foo;
		$foo->short('bar', [
			['trim', ':param1'],
			[$invalidFunction, ':return1']
		]);

		$foo->bar(' foo ');	
	}

	/**
	 * It is complicated, to new methods exists by 
	 * method_exists, we needs create a php extension
	 * @test
	 */ 
	public function should_return_false_with_method_exists()
	{
		$foo = new Foo;
		$foo->bla = function(){
			echo 'ops!';
		};

		$this->assertFalse(method_exists($foo, 'bla'));
	}

	/**
	* @test
	*/ 
	public function should_return_true_with_dynamic_method_exists()
	{
		$foo = new Foo;
		$foo->split = 'trim';
		$foo->short('write', [
			['trim', ':param1'],
			['printf', ':return1']
		])->def('next', function($number){
			return $number + 1;
		}); 

		$this->assertTrue(Helper::dynamic_method_exists('split', $foo));
	}

	/**
	* @test
	*/
	public function should_copy_method_between_callers()
	{
		$foo = new Foo;
		$foo->beInvisible = function(){
			return 'Im invisible!';
		};

		$bar = new \Gandalf\Tests\Fixtures\Bar; 
		$this->assertFalse(Helper::dynamic_method_exists('beInvisible', $bar));
		
		$foo->copy($foo->beInvisible, $bar);
		$this->assertTrue(Helper::dynamic_method_exists('beInvisible', $bar));
	}

	/**
	* @test
	*/
	public function should_move_method_between_callers()
	{
		$foo = new Foo;
		$foo->beInvisible = function(){
			return 'Im invisible!';
		};

		$bar = new \Gandalf\Tests\Fixtures\Bar; 
		$this->assertFalse(Helper::dynamic_method_exists('beInvisible', $bar));
		
		$foo->move($foo->beInvisible, $bar);
		$this->assertTrue(Helper::dynamic_method_exists('beInvisible', $bar));
		$this->assertFalse(Helper::dynamic_method_exists('beInvisible', $foo));
		
	}

	/**
	* @test
	*/
	public function should_not_acess_real_field()
	{
		$foo = new Foo;
		$foo->realField = 'Bolseiro';
		$foo->beInvisible = function() {
			return "{$this->realField}, Im invisible!";
		};

		$this->assertEquals(', Im invisible!', $foo->beInvisible());
	}

	/**
	* @test
	*/
	public function should_acess_real_field()
	{
		$foo = new Foo;
		$foo->realField = 'Bolseiro';
		$foo->beInvisible = function() use ($foo){
			return "{$foo->realField}, Im invisible!";
		};

		$this->assertEquals('Bolseiro, Im invisible!', $foo->beInvisible());
	}

	/**
	* @test
	* @expectedException \InvalidArgumentException
	*/ 
	public function should_emit_error_calling_dynamic_method_exists_with_non_object()
	{
		Helper::dynamic_method_exists('foo', 'bar');
	}

	/** 
	* @test
	*/ 
	public function should_check_real_method()
	{
		$foo = new Foo;

		$this->assertTrue(Helper::dynamic_method_exists('realMethod', $foo));
	}

}