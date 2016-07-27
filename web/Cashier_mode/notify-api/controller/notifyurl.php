<?php
    /**
     * 所有涉及到回调的api接口入口
     * 新浪异步通知方式为post通知。
     */
    @include_once(dirname(__File__) . "/../api/weibopay.class.php");
    ksort($_POST);
    $weibopay = new Weibopay ();
    //记录返回内容
    $weibopay->write_log("收到异步通知请求");
    if ($weibopay->checkSignMsg($_POST, @$_POST["sign_type"],@$_POST["_input_charset"])) {

        switch ($_GET["notify_type"]) {
            //交易结果通知
            case "trade_status_sync":
                //按照自己的业务需求获取对应参数进行保存
                $weibopay->write_log("获取到trade_status_sync结果通知:时间:" . date("YmdHis"));
                $weibopay->write_log("获取到trade_status_sync结果通知:单号:" . json_encode($_POST));
                break;
            //交易退款结果通知
            case "refund_status_sync":
                //按照自己的业务需求获取对应参数进行保存
                $weibopay->write_log("获取到refund_status_sync结果通知:时间:" . date("YmdHis"));
                $weibopay->write_log("获取到refund_status_sync结果通知:单号:" . json_encode($_POST));
                break;
            //充值结果通知
            case "deposit_status_sync":
                //按照自己的业务需求获取对应参数进行保存
                $weibopay->write_log("获取到deposit_status_sync结果通知:时间:" . date("YmdHis"));
                $weibopay->write_log("获取到deposit_status_sync结果通知:单号:" . json_encode($_POST));
                break;
            //提现结果通知
            case "withdraw_status_sync":
                //按照自己的业务需求获取对应参数进行保存
                $weibopay->write_log("获取到withdraw_status_sync结果通知:时间:" . date("YmdHis"));
                $weibopay->write_log("获取到withdraw_status_sync结果通知:单号:" . json_encode($_POST));
                break;
            //批量代付结果通知
            case "batch_trade_status_sync":
                //按照自己的业务需求获取对应参数进行保存
                $weibopay->write_log("获取到batch_trade_status_sync结果通知:时间:" . date("YmdHis"));
                $weibopay->write_log("获取到batch_trade_status_sync结果通知:单号:" . json_encode($_POST));
                break;
            //审核结果通知
            case "audit_status_sync":
                //按照自己的业务需求获取对应参数进行保存
                $weibopay->write_log("获取到audit_status_sync结果通知:时间:" . date("YmdHis"));
                $weibopay->write_log("获取到audit_status_sync结果通知:单号:" . json_encode($_POST));
                break;
            //标的状态通知
            case "bid_status_sync":
                //按照自己的业务需求获取对应参数进行保存
                $weibopay->write_log("获取到bid_status_sync结果通知:时间:" . date("YmdHis"));
                $weibopay->write_log("获取到bid_status_sync结果通知:单号:" . json_encode($_POST));
                break;

            default:
                $weibopay->write_log("获取到未知结果通知:时间:" . date("YmdHis"));
                $weibopay->write_log("获取到未知结果通知:单号:" . json_encode($_POST));
                echo "通知类型错误！";
        }
        // 如果回调成功，需要输出SUCCESS告知新浪回调服务器，已经收到异步通知。
        echo 'success';
    } else {
        $msg = "签名错误 or 非法请求";
        $weibopay->write_log($msg);
        die ("sign error");
    }
?>
