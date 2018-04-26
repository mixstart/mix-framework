<?php

namespace mix\console;

use mix\base\BaseObject;

/**
 * Command类
 * @author 刘健 <coder.liu@qq.com>
 */
class Command extends BaseObject
{

    /**
     * 输出类
     * @var \mix\console\Input
     */
    public $input;

    /**
     * 输出类
     * @var \mix\console\Output
     */
    public $output;

    // 重写构造方法
    public function __construct($attributes = [])
    {
        // 选项导入属性
        $options       = $this->options();
        $optionAliases = $this->optionAliases();
        foreach ($attributes as $name => $attribute) {
            // 全名
            if (in_array($name, $options)) {
                $this->$name = $attribute;
            }
            // 别名
            if (isset($optionAliases[$name]) && in_array($optionAliases[$name], $options)) {
                $fullname        = $optionAliases[$name];
                $this->$fullname = $attribute;
            }
        }
        // 导入输入输出实例
        $this->input  = \Mix::app()->input;
        $this->output = \Mix::app()->output;
        // 调用父类构造方法
        parent::__construct([]);
    }

    // 选项配置
    public function options()
    {
        return [];
    }

    // 选项别名配置
    public function optionAliases()
    {
        return [];
    }

}
