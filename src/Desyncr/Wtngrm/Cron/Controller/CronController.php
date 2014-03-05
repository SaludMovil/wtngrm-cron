<?php
/**
 * Desyncr\Wtngrm\Cron\Controller
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\Wtngrm\Cron\Controller
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://github.com/desyncr
 */
namespace Desyncr\Wtngrm\Cron\Controller;

use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Desyncr\Wtngrm\Service\ServiceInterface;

/**
 * Class CronController
 *
 * @category General
 * @package  Desyncr\Wtngrm\Cron\Controller
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://github.com/desyncr
 */
class CronController extends AbstractActionController
{
    /**
     * @var \Desyncr\Wtngrm\Service\ServiceInterface
     */
    protected $cronService = null;

    /**
     * Registers cronjobs
     *
     * @return null
     * @throws \RuntimeException
     */
    public function executeAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can call this action from web');
        }

        $cronService = $this->getCronService();
        foreach ($cronService->getCronWorkers() as $name => $val) {
            $cronService->schedule($name, new \DateTime());
        }
    }

    /**
     * getCronService
     *
     * @return \Desyncr\Wtngrm\Cron\Service\CronService
     */
    public function getCronService()
    {
        return $this->cronService ?:
            $this->cronService = $this->getServiceLocator()->get(
                'Desyncr\Wtngrm\Cron\Service\CronService'
            );
    }

    /**
     * setCronService
     *
     * @param ServiceInterface $cronService Cron Service
     *
     * @return mixed
     */
    public function setCronService(ServiceInterface $cronService)
    {
        $this->cronService = $cronService;
    }
}
