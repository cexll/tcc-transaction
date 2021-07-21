<?php

declare(strict_types=1);
/**
 * This is a TCC distributed transaction component.
 * @link     https://github.com/luzzhong/tcc-transaction
 * @document https://github.com/luzzhong/tcc-transaction/blob/master/README.md
 * @license  https://github.com/luzzhong/tcc-transaction/blob/master/LICENSE
 */
namespace LoyaltyLu\TccTransaction\Util;

use Hyperf\Guzzle\ClientFactory;

/**
 * 钉钉 发送工具类
 * Class Dingtalk.
 */
class Dingtalk
{
    private $dingtalkHookUrl;

    /**
     * @var \Hyperf\Guzzle\ClientFactory
     */
    private $clientFactory;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    /**
     * 设置hook url信息.
     */
    public function setHookUrl(string $hookUrl)
    {
        $this->dingtalkHookUrl = $hookUrl;
    }

    public function sendText($content, $atUser = [], $isAtAll = false)
    {
        if (empty($this->dingtalkHookUrl)) {
            return false;
        }
        $options = [
            'timeout' => 5.0,
        ];
        $client = $this->clientFactory->create($options);
        $requestData = [];
        $requestData['msgtype'] = 'text';
        $requestData['text'] = [];
        $requestData['text']['content'] = $content;
        $requestData['at'] = [];
        $requestData['at']['atMobiles'] = $atUser;
        $requestData['at']['isAtAll'] = $isAtAll;
        try {
            return $client->post($this->dingtalkHookUrl, ['json' => $requestData]);
        } catch (\Exception $err) {
            return false;
        }
    }

    /**
     * markdown 格式数据推送
     * 钉钉支持语法.
     *
     * 标题
     * # 一级标题
     * ## 二级标题
     * ### 三级标题
     * #### 四级标题
     * ##### 五级标题
     * ###### 六级标题
     *
     * 引用
     * > A man who stands for nothing will fall for anything.
     *
     * 文字加粗、斜体
     **bold**
     *italic*
     *
     * 链接
     * [this is a link](http://name.com)
     *
     * 图片
     * ![](http://name.com/pic.jpg)
     *
     * 无序列表
     * - item1
     * - item2
     *
     * 有序列表
     * 1. item1
     * 2. item2
     * @param $title
     * @param $markdownContent
     * @param mixed $atUser
     * @param mixed $isAtAll
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return false|\Psr\Http\Message\ResponseInterface
     */
    public function sendMarkDown($title, $markdownContent, $atUser = [], $isAtAll = false)
    {
        if (empty($this->dingtalkHookUrl)) {
            return false;
        }
        $options = [
            'timeout' => 5.0,
        ];
        $client = $this->clientFactory->create($options);
        $requestData = [];
        $requestData['msgtype'] = 'markdown';
        $requestData['markdown'] = [];
        $requestData['markdown']['title'] = $title;
        $requestData['markdown']['text'] = $markdownContent;
        $requestData['at'] = [];
        $requestData['at']['atMobiles'] = $atUser;
        $requestData['at']['isAtAll'] = $isAtAll;
        try {
            return $client->post($this->dingtalkHookUrl, ['json' => $requestData]);
        } catch (\Exception $err) {
            return false;
        }
    }
}
