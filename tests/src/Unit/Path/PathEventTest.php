<?php

namespace Drupal\Tests\hook_event_dispatcher\Unit\Path;

use Drupal;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Drupal\Tests\hook_event_dispatcher\Unit\HookEventDispatcherManagerSpy;
use Drupal\Tests\UnitTestCase;
use function hook_event_dispatcher_path_delete;
use function hook_event_dispatcher_path_insert;
use function hook_event_dispatcher_path_update;

/**
 * Class PathEventTest.
 *
 * @package Drupal\Tests\hook_event_dispatcher\Unit\Form
 *
 * @group hook_event_dispatcher
 */
class PathEventTest extends UnitTestCase {

  /**
   * The manager.
   *
   * @var \Drupal\Tests\hook_event_dispatcher\Unit\HookEventDispatcherManagerSpy
   */
  private $manager;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    $builder = new ContainerBuilder();
    $this->manager = new HookEventDispatcherManagerSpy();
    $builder->set('hook_event_dispatcher.manager', $this->manager);
    $builder->compile();
    Drupal::setContainer($builder);
  }

  /**
   * Test PathDeleteEvent.
   */
  public function testPathDeleteEvent(): void {
    $source = 'testSource';
    $alias = 'testAlias';
    $langcode = 'NL';
    $pid = 1337;
    $path = [
      'source' => $source,
      'alias' => $alias,
      'langcode' => $langcode,
      'pid' => $pid,
      'redirect' => TRUE,
    ];

    hook_event_dispatcher_path_delete($path);

    /* @var \Drupal\hook_event_dispatcher\Event\Path\PathDeleteEvent $event */
    $event = $this->manager->getRegisteredEvent(HookEventDispatcherInterface::PATH_DELETE);
    $this->assertSame($source, $event->getSource());
    $this->assertSame($alias, $event->getAlias());
    $this->assertSame($langcode, $event->getLangcode());
    $this->assertSame($pid, $event->getPid());
    $this->assertTrue($event->isRedirect());
  }

  /**
   * Test PathDeleteEvent.
   */
  public function testPathDeleteEventWithoutRedirect(): void {
    $source = 'testSource';
    $alias = 'testAlias';
    $langcode = 'NL';
    $pid = 1337;
    $path = [
      'source' => $source,
      'alias' => $alias,
      'langcode' => $langcode,
      'pid' => $pid,
    ];

    hook_event_dispatcher_path_delete($path);

    /* @var \Drupal\hook_event_dispatcher\Event\Path\PathDeleteEvent $event */
    $event = $this->manager->getRegisteredEvent(HookEventDispatcherInterface::PATH_DELETE);
    $this->assertSame($source, $event->getSource());
    $this->assertSame($alias, $event->getAlias());
    $this->assertSame($langcode, $event->getLangcode());
    $this->assertSame($pid, $event->getPid());
    $this->assertFalse($event->isRedirect());
  }

  /**
   * Test PathInsertEvent.
   */
  public function testPathInsertEvent(): void {
    $source = 'testSource';
    $alias = 'testAlias';
    $langcode = 'NL';
    $pid = 1337;
    $path = [
      'source' => $source,
      'alias' => $alias,
      'langcode' => $langcode,
      'pid' => $pid,
    ];

    hook_event_dispatcher_path_insert($path);

    /* @var \Drupal\hook_event_dispatcher\Event\Path\PathInsertEvent $event */
    $event = $this->manager->getRegisteredEvent(HookEventDispatcherInterface::PATH_INSERT);
    $this->assertSame($source, $event->getSource());
    $this->assertSame($alias, $event->getAlias());
    $this->assertSame($langcode, $event->getLangcode());
    $this->assertSame($pid, $event->getPid());
  }

  /**
   * Test PathUpdateEvent.
   */
  public function testPathUpdateEvent(): void {
    $source = 'testSource';
    $alias = 'testAlias';
    $langcode = 'NL';
    $pid = 1337;
    $path = [
      'source' => $source,
      'alias' => $alias,
      'langcode' => $langcode,
      'pid' => $pid,
    ];

    hook_event_dispatcher_path_update($path);

    /* @var \Drupal\hook_event_dispatcher\Event\Path\PathUpdateEvent $event */
    $event = $this->manager->getRegisteredEvent(HookEventDispatcherInterface::PATH_UPDATE);
    $this->assertSame($source, $event->getSource());
    $this->assertSame($alias, $event->getAlias());
    $this->assertSame($langcode, $event->getLangcode());
    $this->assertSame($pid, $event->getPid());
  }

  /**
   * Test with empty path array.
   */
  public function testWithEmptyPath(): void {
    $this->manager->setMaxEventCount(0);

    hook_event_dispatcher_path_delete([]);
    hook_event_dispatcher_path_insert([]);
    hook_event_dispatcher_path_update([]);

    // Add this check so phpunit won't trigger incomplete test.
    $this->assertTrue(TRUE);
  }

}
