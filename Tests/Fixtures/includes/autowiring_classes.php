<?php

namespace Symfony\Component\DependencyInjection\Tests\Compiler;

use Psr\Log\LoggerInterface;

if (PHP_VERSION_ID >= 80000) {
    require __DIR__.'/uniontype_classes.php';
    require __DIR__.'/autowiring_classes_80.php';
}

class Foo
{
}

class Bar
{
    public function __construct(Foo $foo)
    {
    }
}

interface AInterface
{
}

class A implements AInterface
{
    public static function create(Foo $foo)
    {
    }
}

class B extends A
{
}

class C
{
    public function __construct(A $a)
    {
    }
}

interface DInterface
{
}

interface EInterface extends DInterface
{
}

interface IInterface
{
}

class I implements IInterface
{
}

class F extends I implements EInterface
{
}

class G
{
    public function __construct(DInterface $d, EInterface $e, IInterface $i)
    {
    }
}

class H
{
    public function __construct(B $b, DInterface $d)
    {
    }
}

class D
{
    public function __construct(A $a, DInterface $d)
    {
    }
}

class E
{
    public function __construct(D $d = null)
    {
    }
}

class J
{
    public function __construct(I $i)
    {
    }
}

class K
{
    public function __construct(IInterface $i)
    {
    }
}

interface CollisionInterface
{
}

class CollisionA implements CollisionInterface
{
}

class CollisionB implements CollisionInterface
{
}

class CannotBeAutowired
{
    public function __construct(CollisionInterface $collision)
    {
    }
}

class Lille
{
}

class Dunglas
{
    public function __construct(Lille $l)
    {
    }
}

class LesTilleuls
{
    public function __construct(Dunglas $j, Dunglas $k)
    {
    }
}

class OptionalParameter
{
    public function __construct(CollisionInterface $c = null, A $a, Foo $f = null)
    {
    }
}

class BadTypeHintedArgument
{
    public function __construct(Dunglas $k, NotARealClass $r)
    {
    }
}
class BadParentTypeHintedArgument
{
    public function __construct(Dunglas $k, OptionalServiceClass $r)
    {
    }
}
class NotGuessableArgument
{
    public function __construct(Foo $k)
    {
    }
}
class NotGuessableArgumentForSubclass
{
    public function __construct(A $k)
    {
    }
}
class MultipleArguments
{
    public function __construct(A $k, $foo, Dunglas $dunglas, array $bar)
    {
    }
}

class MultipleArgumentsOptionalScalar
{
    public function __construct(A $a, $foo = 'default_val', Lille $lille = null)
    {
    }
}
class MultipleArgumentsOptionalScalarLast
{
    public function __construct(A $a, Lille $lille, $foo = 'some_val')
    {
    }
}

/*
 * Classes used for testing createResourceForClass
 */
class ClassForResource
{
    public function __construct($foo, Bar $bar = null)
    {
    }

    public function setBar(Bar $bar)
    {
    }
}
class IdenticalClassResource extends ClassForResource
{
}

class ClassChangedConstructorArgs extends ClassForResource
{
    public function __construct($foo, Bar $bar, $baz)
    {
    }
}

class SetterInjectionCollision
{
    /**
     * @required
     */
    public function setMultipleInstancesForOneArg(CollisionInterface $collision)
    {
        // The CollisionInterface cannot be autowired - there are multiple

        // should throw an exception
    }
}

class SetterInjection extends SetterInjectionParent
{
    /**
     * @required
     */
    public function setFoo(Foo $foo)
    {
        // should be called
    }

    /** @inheritdoc*/ // <- brackets are missing on purpose
    public function setDependencies(Foo $foo, A $a)
    {
        // should be called
    }

    /** {@inheritdoc} */
    public function setWithCallsConfigured(A $a)
    {
        // this method has a calls configured on it
    }

    public function notASetter(A $a)
    {
        // should be called only when explicitly specified
    }

    /**
     * @required*/
    public function setChildMethodWithoutDocBlock(A $a)
    {
    }
}

class Wither
{
    public $foo;

    /**
     * @required
     */
    public function setFoo(Foo $foo)
    {
    }

    /**
     * @required
     * @return static
     */
    public function withFoo1(Foo $foo): self
    {
        return $this->withFoo2($foo);
    }

    /**
     * @required
     * @return static
     */
    public function withFoo2(Foo $foo): self
    {
        $new = clone $this;
        $new->foo = $foo;

        return $new;
    }
}

class SetterInjectionParent
{
    /** @required*/
    public function setDependencies(Foo $foo, A $a)
    {
        // should be called
    }

    public function notASetter(A $a)
    {
        // @required should be ignored when the child does not add @inheritdoc
    }

    /**	@required <tab> prefix is on purpose */
    public function setWithCallsConfigured(A $a)
    {
    }

    /** @required */
    public function setChildMethodWithoutDocBlock(A $a)
    {
    }
}

class NotWireable
{
    public function setNotAutowireable(NotARealClass $n)
    {
    }

    public function setNotAutowireableBecauseOfATypo(lesTilleuls $sam)
    {
    }

    public function setBar()
    {
    }

    public function setOptionalNotAutowireable(NotARealClass $n = null)
    {
    }

    public function setDifferentNamespace(\stdClass $n)
    {
    }

    public function setOptionalNoTypeHint($foo = null)
    {
    }

    public function setOptionalArgNoAutowireable($other = 'default_val')
    {
    }

    /** @required */
    protected function setProtectedMethod(A $a)
    {
    }
}

class PrivateConstructor
{
    private function __construct()
    {
    }
}

class ScalarSetter
{
    /**
     * @required
     */
    public function setDefaultLocale($defaultLocale)
    {
    }
}

interface DecoratorInterface
{
}

class Decorated implements DecoratorInterface
{
    public function __construct($quz = null, \NonExistent $nonExistent = null, DecoratorInterface $decorated = null, array $foo = [])
    {
    }
}

class Decorator implements DecoratorInterface
{
    public function __construct(LoggerInterface $logger, DecoratorInterface $decorated)
    {
    }
}

class DecoratedDecorator implements DecoratorInterface
{
    public function __construct(DecoratorInterface $decorator)
    {
    }
}

class NonAutowirableDecorator implements DecoratorInterface
{
    public function __construct(LoggerInterface $logger, DecoratorInterface $decorated1, DecoratorInterface $decorated2)
    {
    }
}

final class ElsaAction
{
    public function __construct(NotExisting $notExisting)
    {
    }
}
