<?php

class sfDoctrineiCalStoragePluginConfiguration extends sfPluginConfiguration
{

  /**
   * @see sfPluginConfiguration
   */
  public function initialize() {

    $this->dispatcher->connect('doctrine.configure_connection', array($this, 'configureDoctrineConnection'));
  }

  public function configureDoctrineConnection(sfEvent $event)
  {

    $connection = $event['connection'];

    $connection->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
    $connection->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, 'use_native_enum', true);
  }
}
