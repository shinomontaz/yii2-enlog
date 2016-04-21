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

  /**
   * 
   * @param type $method
   * @param type $params
   * @return type
   * @throws \Exception
   */
	public function request( $method, $params )
	{
		// create payment
		$request = [
			'jsonrpc' => '2.0',
			'method'  => $method,
			'params'  => $params,
			'id'      => md5(microtime()),
		];

		$jsonRequest = json_encode($request);

		$ctx = stream_context_create([
			'http' => [
				'method'  => 'POST',
				'header'  =>	'Content-Type: application/json-rpc' . "\r\n".
											'Rpc-User: '. $this->name . "\r\n" .
											'Rpc-Hash: '. $this->pass . "\r\n",
				'content' => $jsonRequest
			],
			"ssl"=> [
        "verify_peer"=>false,
        "verify_peer_name"=>false,
				'allow_self_signed' => true,
	    ]
		]);

		$jsonResponse = '';
		$jsonResponse = file_get_contents($this->url, false, $ctx);
		$jsonResponse = json_decode( $jsonResponse );
		if( !isset( $jsonResponse->result ) ) {
			throw new \Exception( \yii\helpers\VarDumper::dumpAsString($jsonResponse) );
		}
		else if( $jsonResponse->result && !isset( $jsonResponse->result->error ) ) {
			return $jsonResponse->result->task;
		}
		else {
			throw new \Exception( $jsonResponse->result->error );
		}
	}
}
