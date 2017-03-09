<?php

namespace App\Forms;

use Nette\Forms\Container;
use Nette\Forms\IControl;

/**
 * Extends form library with additional methods.
 */
class ExtendingMethods
{
    public function registerMethods()
    {
        Container::extensionMethod('autoFill', function (Container $container, $values, $erase = false) {
            if ($values instanceof \Traversable) {
                $values = iterator_to_array($values);
            }

            foreach ($container->getComponents() as $name => $control) {
                if ($control instanceof IControl) {
                    if (is_array($values)) {
                        if (array_key_exists($name, $values)) {
                            $control->setValue($values[$name]);
                        } elseif ($erase) {
                            $control->setValue(null);
                        }
                    } else {
                        if (isset($values->{$name})) {
                            $control->setValue($values->{$name});
                        } elseif ($erase) {
                            $control->setValue(null);
                        }
                    }
                } elseif ($control instanceof Container) {
                    if (is_array($values)) {
                        if (array_key_exists($name, $values)) {
                            $control->autoFill($values[$name], $erase);
                        } elseif ($erase) {
                            $control->autoFill(array(), $erase);
                        }
                    } else {
                        if (isset($values->{$name})) {
                            $control->autoFill($values->{$name});
                        } elseif ($erase) {
                            $control->autoFill(null);
                        }
                    }
                }
            }

            return $this;
        });
    }
}
