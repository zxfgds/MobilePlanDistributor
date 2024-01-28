<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ModifyClientUsersTable extends AbstractMigration
{
	/**
	 * Change Method.
	 *
	 * Write your reversible migrations using this method.
	 *
	 * More information on writing migrations is available here:
	 * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
	 *
	 * Remember to call "create()" or "update()" and NOT "save()" when working
	 * with the Table class.
	 */
	public function change ()
	: void
	{
		$table = $this->table('client_users');
		if ($table->hasColumn('wechat_open_id')) {
			$table->removeColumn('wechat_open_id', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => '微信openid']);
		}
		if ($table->hasColumn('alipay_open_id')) {
			$table->removeColumn('alipay_open_id', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => '支付宝openid']);
		}
		if ($table->hasColumn('douyin_open_id')) {
			$table->removeColumn('douyin_open_id', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => '抖音openid']);
		}
		if ($table->hasColumn('kuaishou_open_id')) {
			$table->removeColumn('kuaishou_open_id', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => '快手openid']);
		}
		if ($table->hasColumn('toutiao_open_id')) {
			$table->removeColumn('toutiao_open_id', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => '头条openid']);
		}
		if ($table->hasColumn('baidu_open_id')) {
			$table->removeColumn('baidu_open_id', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => '百度openid']);
		}
		if ($table->hasColumn('qq_open_id')) {
			$table->removeColumn('qq_open_id', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => 'QQ openid']);
		}
		if ($table->hasColumn('weibo_open_id')) {
			$table->removeColumn('weibo_open_id', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => '微博openid']);
		}
		if ($table->hasColumn('quick_open_id')) {
			$table->removeColumn('quick_open_id', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => '快应用openid']);
		}
		if ($table->hasColumn('quick_huawei_open_id')) {
			$table->removeColumn('quick_huawei_open_id', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => '快应用华为openid']);
		}
		if ($table->hasColumn('wechat_user_info')) {
			$table->removeColumn('wechat_user_info', 'json', ['null' => TRUE, 'comment' => '微信用户信息']);
		}
		if ($table->hasColumn('douyin_user_info')) {
			$table->removeColumn('douyin_user_info', 'json', ['null' => TRUE, 'comment' => '抖音用户信息']);
		}
		if ($table->hasColumn('kuaishou_user_info')) {
			$table->removeColumn('kuaishou_user_info', 'json', ['null' => TRUE, 'comment' => '快手用户信息']);
		}
		if ($table->hasColumn('toutiao_user_info')) {
			$table->removeColumn('toutiao_user_info', 'json', ['null' => TRUE, 'comment' => '头条用户信息']);
		}
		if ($table->hasColumn('baidu_user_info')) {
			$table->removeColumn('baidu_user_info', 'json', ['null' => TRUE, 'comment' => '百度用户信息']);
		}
		if ($table->hasColumn('weibo_user_info')) {
			$table->removeColumn('weibo_user_info', 'json', ['null' => TRUE, 'comment' => '微博用户信息']);
		}
		if ($table->hasColumn('quick_user_info')) {
			$table->removeColumn('quick_user_info', 'json', ['null' => TRUE, 'comment' => '快应用用户信息'])
			      ->removeColumn('quick_huawei_user_info', 'json', ['null' => TRUE, 'comment' => '快应用华为用户信息'])
			      ->save();
		}

	}
}