<?php

namespace App\Tests;

use Fusio\Cli\Config\Config;
use Fusio\Cli\Config\ConfigInterface;
use Fusio\Cli\Service\Authenticator;
use Fusio\Impl\Cli\Transport;
use PSX\Engine\DispatchInterface;
use PSX\Framework\Test\ControllerDbTestCase;
use PSX\Framework\Test\Environment;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

abstract class ApiTestCase extends ControllerDbTestCase
{
    /**
     * Contains the access token which you can use to access all endpoints of your API
     */
    protected ?string $accessToken = null;

    public function getDataSet(): array
    {
        return Fixture::getFixture()->toArray();
    }

    protected function setUp(): void
    {
        parent::setUp();

        // run deploy
        $application = Environment::getService(Application::class);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $this->runLogin($application);
        $this->runDeploy($application);
        $this->accessToken = $this->getAccessToken();
    }

    private function runLogin(Application $application): void
    {
        $input = new ArrayInput([
            'command'    => 'login',
            '--username' => Fixture::TEST_USERNAME,
            '--password' => Fixture::TEST_PASSWORD,
        ]);

        $output = new BufferedOutput();
        $exitCode = $application->run($input, $output);

        if ($exitCode !== 0) {
            throw new \RuntimeException('Could not execute login command: ' . $output->fetch());
        }
    }

    private function runDeploy(Application $application): void
    {
        $input = new ArrayInput([
            'command' => 'deploy',
            'file'    => __DIR__ . '/../.fusio.yml',
        ]);

        $output = new BufferedOutput();
        $exitCode = $application->run($input, $output);

        if ($exitCode !== 0) {
            throw new \RuntimeException('Could not execute deploy command: ' . $output->fetch());
        }
    }

    private function getAccessToken(): string
    {
        $config = new Config();
        $config->setBaseDir(__DIR__ . '/../');

        $transport = new Transport(Environment::getService(DispatchInterface::class));
        $authenticator = new Authenticator($transport, $config);

        return $authenticator->getAccessToken();
    }
}
