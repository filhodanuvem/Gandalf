Gandalf  [![Build Status](https://travis-ci.org/cloudson/Gandalf.png?branch=master)](https://travis-ci.org/cloudson/Gandalf)
====================

Gandalf is a (crazy) php library that handles functions.

### Where use?
- php5.4+ 
- Where there are courageous people


### Why use? 
As we know, our prefer language has object
semi opened, I mean, we can define instance fields in execution time, see below: 

```php
<?php

class Baggin
{

}

$bilbo = new Baggin;
$bilbo->hasRing =  true; 

var_dump($bilbo->hasRing); // true

```  

But, we couldn't insert methods as functional programming languages (javascript, ruby ...) 

```php
<?php

class Elf
{

}

$legolas = new Elf;
$legolas->attack = function () {
    echo 'Goooo!';
};

$legolas->attack(); // Fatal error: Call to undefined method Elf::attack()

```

### How use it

Use our trait:

```php 
<?php

class Elf
{
    use Gandalf\Entity\Caller;
}

$legolas = new Elf;
$legolas->attack = function () {
    echo 'Goooo!';
};

$legolas->attack(); // Goooo! =) 

```

### Define function pattern

In Doctrine\ORM exists a method dynamic for search entities: 

```php
<?php

$repository->findOneByName('bar');
$repository->findByPlace('Middle earth');

```

with Gandalf you can write similar methods that use regex pattern, see below:

```php
<?php

$legolas = new Elf;
$legolas->def('findBy([A-Z][a-z]+)', function($value){
    return "Find by {$this->_1}";
});

$legolas->findByName('bilbo'); // "return 'Find by Name'"

```
note that `$this->_1` is a group var regex. You could too use var `$this->matches[1]`.

**Important**: $this don't is the current context 

```php
<?php 
$legolas = new Elf;
$legolas->def('find(One){0,1}By([A-Z][a-z]+)', function($value){
    var_dump($this->matches);
});

$legolas->findByName('bilbo'); // "['findByName', null, 'Name']"
$legolas->findOneByFamily('bilbo'); // "['findOneByFamily', null, 'Family']"

```

### Creating shortcuts

Many times, we need write compound calls like 

```php
<?php
return str_replace(' ', '-', strtolower(trim($foo)));

```

with Gandalf you can write this

```php
<?php
$foo = new Elf;
$foo->short('getSlug', [
            ['trim', ":param1"],
            ['strtolower', ":return1"],
            ['str_replace',' ', '-',":return2"],
        ]);
$foo->getSlug('How use crazy Gandalf lib!'); // how-use-crazy-gandalf-lib
```


### Why you should be careful before uses this library on production.

* Galdalf is a wizard, and wizards do magic. So, this library uses many php magic methods. The performance of the Galdalf can be a problem to your application. 
* For now, it's possible create a function with invalid name. E.g; a regex '/[a-z]#[a-z]/' is acceptable, but you never will call $foo->a#a() . 
* 

### And...

Contact-me on [twitter](http://twitter.com/cloudson) or <cloudson@outlook.com> if you want talk about this project. It would be awesome! 


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/cloudson/gandalf/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

