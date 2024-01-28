<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateProductPackagesTable extends AbstractMigration
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
    public function change(): void
    {
        $commissionConfigArray = [
            'create'       => [
                'status'  => FALSE,
                'level_1' => 0,
                'level_2' => 0,
            ],
            'paid'         => [
                'status' => FALSE,
                'config' => [
                    'level_1' => 0,
                    'level_2' => 0,
                ],
            ],
            'active'       => [
                'status' => FALSE,
                'config' => [
                    'level_1' => 0,
                    'level_2' => 0,
                ],
            ],
            'first_charge' => [
                'status' => FALSE,
                'config' => [
                    'level_1' => 0,
                    'level_2' => 0,
                ],
            ],
            'online'       => [
                'status' => FALSE,
                'config' => [
                    'month_1' => [
                        'level_1' => 0,
                        'level_2' => 0,
                    ],
                ],
            ],
        ];
        
        $formConfigArray = [
            //                '收件地址', '收件人','手机号码','详细信息'
            //                  '开卡证件姓名','证件号码',
            //                  '证件正面' ,'证件反面', '手持证件正面','开卡人正面'
            'receiver_region'    => TRUE,
            'receiver_name'      => TRUE,
            'receiver_phone_num' => TRUE,
            'receiver_address'   => TRUE,
            'card_name'          => FALSE,
            'card_num'           => FALSE,
            'card_pic'           => FALSE,
            'user_card_pic'      => FALSE,
            'user_pic'           => FALSE,
        ];
        
        $table = $this->table('product_packages');
        $table->addColumn('name', 'string', ['null' => FALSE, 'comment' => '套餐名称'])
              ->addColumn('operator', 'integer', ['null' => FALSE, 'comment' => '运营商', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'signed' => FALSE])
              ->addColumn('type', 'integer', ['default' => 0, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'comment' => '类型'])
              ->addColumn('cover', 'string', ['null' => FALSE, 'comment' => '封面图/主图'])
              ->addColumn('images', 'json', ['null' => TRUE, 'comment' => '底部图片'])
              ->addColumn('status', 'integer', ['null' => FALSE, 'comment' => '状态', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'signed' => FALSE, 'default' => 0])
              ->addColumn('standard_config', 'json', ['null' => FALSE, 'comment' => '规格配置']) // 'default' => '{"fee":0,"voice":0,"data":0,"data_direct":0,"sms":0}'
              ->addColumn('code', 'string', ['null' => FALSE, 'comment' => '本地编码'])
              ->addColumn('price', 'string', ['null' => TRUE, 'default' => 0, 'comment' => '售价'])
              ->addColumn('price_show', 'string', ['null' => TRUE, 'default' => 0, 'comment' => '售价'])
              ->addColumn('interceptor', 'json', ['null' => TRUE, 'comment' => '本地拦截'])
              ->addColumn('agreement', 'json', ['null' => TRUE, 'comment' => '挂载协议'])
              ->addColumn('stock_status', 'boolean', ['default' => FALSE, 'comment' => '启用库存'])
              ->addColumn('stock_num', 'integer', ['default' => 0, 'comment' => '当前库存'])
              ->addColumn('shop_id', 'integer', ['default' => 0, 'comment' => '所属店铺'])
              ->addColumn('select_num_status', 'boolean', ['default' => FALSE, 'comment' => '开启选号'])
              ->addColumn('select_num_config', 'json', ['comment' => '选号配置']) //'default' => '{"local":true,"store_id":0}'
              ->addColumn('carrier_status', 'integer', ['default' => 0, 'comment' => '分销店铺配置'])
              ->addColumn('carrier_config', 'json', ['null' => TRUE, 'comment' => '分销店铺id'])
              ->addColumn('code_remote', 'string', ['null' => TRUE, 'comment' => '外部编码'])
            
            //            表单配置
              ->addColumn('form_config', 'json', ['comment' => '表单配置']) // 'default' => json_encode($formConfigArray),
//            页面配置
              ->addColumn('page_config', 'json', ['comment' => '页面配置']) //'default' => '{"top_image":"","main_color":"#FFF","button":{"text":"立即领取","bg_color":"","color":"","animate":false,"bg_image":"","border_radius":0}}'
//            分销配置
              ->addColumn('commission_total', 'integer', ['default' => 0, 'comment' => '佣金总额'])
              ->addColumn('commission_condition', 'text', ['null' => TRUE, 'comment' => '结算条件说明'])
              ->addColumn('commission_config', 'json', ['null' => TRUE, 'comment' => '佣金配置']) // 'default' => json_encode($commissionConfigArray),
//            限制下单
              ->addColumn('buy_num_valid_status', 'boolean', ['default' => FALSE, 'comment' => '开卡数量限制'])
              ->addColumn('buy_num_valid_config', 'json', ['comment' => '开卡数量限制配置,type // 0:全局,1:同运营商,2:同套餐,3:同店铺']) //'default' => '{"type":0,"num":0}',
//            本地验证
              ->addColumn('local_valid_config', 'json', ['null' => TRUE, 'comment' => '本地校验'])//, 'default' => '[]'
//            运营商接口验证
              ->addColumn('remote_api_status', 'boolean', ['default' => FALSE, 'comment' => '开启远程校验'])
              ->addColumn('remote_api_config', 'json', ['comment' => '远程校验配置']) //'default' => '{api_id:0,product_info:[]}',
//            返费配置
              ->addColumn('recharge_status', 'boolean', ['default' => FALSE, 'comment' => '开启返费'])
              ->addColumn('recharge_config', 'json', ['comment' => '返费配置,show:显示配置(前端配置时候各种通配符),act:实际配置用于系统计算']) //'default' => '{"show":[],"act":[]}',
//            预充值配置
              ->addColumn('pre_charge_status', 'boolean', ['default' => FALSE, 'comment' => '开启预充'])
              ->addColumn('pre_charge_amount', 'integer', ['signed' => FALSE, 'default' => 0, 'comment' => '预充金额'])
//            激活配置
              ->addColumn('active_url', 'string', ['null' => TRUE])
              ->save();
        
        
    }
}
