<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * <http://www.rubber-duckling.net>
 *
 * @license     MIT
 * @link        http://github.com/iwalz/zendserverapi
 * @author      Ingo Walz <ingo.walz@googlemail.com>
 * @package     ZendService\ZendServerAPI\Factories
 */

namespace ZendService\ZendServerAPI\Factories;

/**
 * A factory, that retrieves commands from the webapi version 1.1.
 * Used for the Zend Server version 5.5. Can also handle the commands from
 * 1.0
 *
 * @license     MIT
 * @link        http://github.com/iwalz/zendserverapi
 * @author      Ingo Walz <ingo.walz@googlemail.com>
 * @package     ZendService\ZendServerAPI\Factories
 */
class ApiVersion11CommandFactory extends ApiVersion10CommandFactory
{
    /**
     * Retrieves the command object and throws an error if
     * the command is not supported via this factory (and the Zend Server/webapi version).
     * Will take care of the commands from the previous factories
     *
     * @throws \RuntimeException
     * @param  string                $name
     * @return \ZendService\ZendServerAPI\Method
     */
    public function factory($name)
    {
        $args = func_get_args();
        array_shift($args);

        switch ($name) {
            case 'clusterReconfigureServer':
            case 'applicationGetStatus':
            case 'applicationDeploy':
            case 'applicationRemove':
            case 'applicationRollback':
            case 'applicationSynchronize':
            case 'applicationUpdate':
                $reflect  = new \ReflectionClass("\\ZendService\\ZendServerAPI\\Method\\" . ucfirst($name));

                return $reflect->newInstanceArgs($args);
                break;
            default:
                return call_user_func_array('parent::factory', array_merge(array($name), $args));
        }
    }
}