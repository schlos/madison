<?php
/**
 * 	Controller for User actions
 */
class UserApiController extends ApiController{

	public function __construct(){
		parent::__construct();

		$this->beforeFilter('auth', array('on' => array('post','put', 'delete')));
	}	
	
	public function getVerify(){
		$this->beforeFilter('admin');

		$requests = UserMeta::where('meta_key', 'verify')->with('user')->get();

		return Response::json($requests);
	}

	public function postVerify(){
		$this->beforeFilter('admin');

		$request = Input::get('request');
		$status = Input::get('status');

		$accepted = array('pending', 'verified', 'denied');

		if(!in_array($status, $accepted)){
			throw new Exception('Invalid value for verify request: ' . $status);
		}

		$meta = UserMeta::find($request['id']);

		$meta->meta_value = $status;

		$ret = $meta->save();

		return Response::json($ret);
	}
}
