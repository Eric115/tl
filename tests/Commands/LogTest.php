<?php
/**
 * @file
 * Contains \Larowlan\Tl\Tests\Commands\LogTest.php
 */

namespace Larowlan\Tl\Tests\Commands;

use Larowlan\Tl\Tests\TlTestBase;
use Larowlan\Tl\Ticket;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * @coversDefaultClass \Larowlan\Tl\Commands\Log
 * @group Commands
 */
class LogTest extends TlTestBase {

  /**
   * @covers \Larowlan\Tl\Application::doRun
   * @covers ::execute
   */
  public function testLogging() {
    $this->getMockConnector()->expects($this->any())
      ->method('ticketDetails')
      ->with(1234)
      ->willReturn(new Ticket('Running tests', 123));
    $output =  new StreamOutput(fopen('php://memory', 'w', false));
    $command = $this->container->get('app.command.start');
    $command->setApplication($this->application);
    $this->application->setAutoExit(FALSE);
    $this->application->run(new ArrayInput([
      'command' => 'start',
      'issue_number' => 1234,
    ]), $output);
    $this->assertTicketIsOpen(1234);
    $output = $this->executeCommand('log');
    $this->assertRegExp('/Started new entry for 1234: Running tests/', $output->getDisplay());
  }

}
