<?php
	
	namespace app\controller\http;
	
	use app\logic\PhoneNumberLogic;
	use app\logic\ProductFancyNumberLogic;
	use app\logic\ProductPackageNumberLogic;
	use support\Response;
	
	class PhoneNumberController extends Controller
	{
		protected string $logic = PhoneNumberLogic::class;
		
		public function packageNumberList(): Response
		{
			$params = $this->params();
			try {
				$list = ProductPackageNumberLogic::getList($params);
				return $this->success($list);
			} catch (\Exception $e) {
				return $this->error($e->getMessage());
			}
		}
		
		public function virtualNumberList(): Response
		{
			$params = $this->params();
			try {
				$list = ProductFancyNumberLogic::getList($params);
				return $this->success($list);
			} catch (\Exception $e) {
				return $this->error($e->getMessage());
			}
		}
	}