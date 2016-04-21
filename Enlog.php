<?php

/**
 * File Enlog.php.
 * @author Denis Rybakov <shinomontaz@gmail.com>
 */

namespace shinomontaz;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Enlog
 * Enlog v1 API implementation
 * Created by: denis.rybakov
 * Date: 10.04.16
 */
class Enlog extends Component
{
	public $url;
	public $name;
	public $pass;
  public $isTest = true;

  /**
   * 
   * @param string $name
   * @param string $pass
   * @return \shinomontaz\Enlog
   */
  public function auth( $name, $pass ) {
		$this->name = $name;
		$this->pass = $pass;
		return $this;
	}
  
  /**
   * 
   * @param string $method
   * @param array $params
   * @return string
   * @throws \Exception
   */
	public function request( $method, $params = [] )
	{
		// create payment
		$request = [
			'jsonrpc' => '2.0',
			'method'  => $method,
			'params'  => $params,
			'id'      => md5(microtime()),
		];

		$jsonRequest = json_encode($request);

    $ctx_options = [
			'http' => [
				'method'  => 'POST',
				'header'  =>	'Content-Type: application/json-rpc' . "\r\n".
											'Rpc-User: '. $this->name . "\r\n" .
											'Rpc-Hash: '. $this->pass . "\r\n",
				'content' => $jsonRequest
			],
		];
    
    if( $this->isTest ) {
      $ctx_options['ssl'] = [
        "verify_peer"=>false,
        "verify_peer_name"=>false,
				'allow_self_signed' => true,
	    ];
    }
    
		$ctx = stream_context_create( $ctx_options );

		$jsonResponse = '';
		$jsonResponse = file_get_contents($this->url, false, $ctx);
		$jsonResponse = json_decode( $jsonResponse );
		if( !isset( $jsonResponse->result ) ) {
			throw new \Exception( \yii\helpers\VarDumper::dumpAsString($jsonResponse) );
		}
		else if( $jsonResponse->result && !isset( $jsonResponse->result->error ) ) {
			return $jsonResponse->result;
		}
		else {
			throw new \Exception( $jsonResponse->result->error );
		}
	}
}
