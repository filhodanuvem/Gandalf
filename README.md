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

But, we couldn't insert methods as a ruby language

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

$repository = $entityManager->getRepositoy('FooBundle:bar');
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

**Important**: $this is other context, and dont current.

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

### And...

Contact-me on [twitter](http://twitter.com/cloudson) or [email](cloudson@outlook.com) if you want talk about this project. It would be awesome! 
