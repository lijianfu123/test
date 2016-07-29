<?php
    /*
    对前面数据进行拼接，计算签名并返回
     */
    @include_once(dirname(__File__) . "/../api/weibopay.class.php");
    $weibopay = new Weibopay ();
    if ($_POST['servicestatus'] == 'start') {
        $filename = array();
        $filename["jymx-zjtg"] = 'jymx-zjtg';//交易明细资金托管
        $filename["zhsyhz-yh-cqg"] = 'zhsyhz-yh-cqg';//账户收益汇总
        $filename["zwmx-pt-rmb"] = 'zwmx-pt-rmb';//账户明细平台
        $filename["zwmx-pt-zj-rmb"] = 'zwmx-pt-zj-rmb';//账务明细-平台中间户
        $filename["zwmx-yh-cqg"] = 'zwmx-yh-cqg';//账务明细-用户-存钱罐
        $filename["zhye-yh-cqg"] = 'zhye-yh-cqg';//存钱罐账户余额及收益
        //$date=date("Ymd");//年月日日期
        $date = '20150623';
        $filetype = ".zip";//目前对账文件都是打成zip压缩包提供下载
        $weibopay->write_log("接收到对账请求,对账日期：" . $date);
        $weibopay->write_log("对账下载文件集合" . json_encode($filename));
        //按照对账日期创建文件夹
        $zipfloresult = $weibopay->mkFolder("/tmp/zip/" . $date . "/");
        $unzipfloresult = $weibopay->mkFolder("/tmp/zip/" . $date . "/unzip/");
        $zipflo = "/tmp/zip/" . $date . "/";
        $unzipflo = "/tmp/zip/" . $date . "/unzip/";
        $zip = new ZipArchive;
        $weibopay->write_log("**************sftp下载对账文件开始*****************");
        foreach ($filename as $key => $value) {
            $result = $weibopay->sftp_download($zipflo, $date . "_" . $value . $filetype);
            if ($result) {
                $weibopay->write_log("*************解压缩文件" . $zipflo . $date . "_" . $value . $filetype);
                $res = $zip->open($zipflo . $date . "_" . $value . $filetype);
                if ($res === TRUE) {
                    $weibopay->write_log("*********接压缩成功");
                    //解压缩到test文件夹
                    $serveresult = $zip->extractTo($unzipflo);
                    if ($serveresult) {
                        $weibopay->write_log("***********保存成功");
                        $weibopay->write_log("***********保存路劲:" . $unzipflo);
                    } else {
                        $weibopay->write_log("***********保存路劲:" . $unzipflo);
                        write_log("************保存失败");
                        die();
                    }
                    $zip->close();
                } else {
                    $weibopay->write_log("***********保存路劲:" . $unzipflo);
                    write_log("*****************解压缩失败" . $zipflo . $date . "_" . $value . $filetype);
                    die();
                }
            } else {
                $weibopay->write_log("***********保存路劲:" . $unzipflo);
                write_log("************下载失败");
                die();
            }
        }
        $weibopay->write_log("************开始解析cvs文件***********");
        $handler = opendir($unzipflo);
        while (($filename = readdir($handler)) !== false) {
            $weibopay->write_log("文件夹中的文件：" . $filename);
            if ($filename != "." && $filename != "..") {
                $row = 1;
                $file = $unzipflo . $filename;
                $weibopay->write_log("解析文件" . $file . "开始");
                echo "解析文件" . $file . "开始";
                $handle = fopen($file, "r");
                $resultarray = array();
                while ($data = fgetcsv($handle)) {
                    //统计数据行数
                    $num = count($data);
                    $row++;
                    //对数组进行迭代，迭代每条数据
                    for ($c = 0; $c < $num; $c++) {
                        //注意中文乱码问题
                        $data[$c] = iconv("gbk", "utf-8//IGNORE", $data[$c]);
                        $weibopay->write_log("***********" . $row . "****");
                        $weibopay->write_log($data[$c]);

                        $weibopay->write_log("***********");
                        //将数据放在2维数组进行存放
                        $resultarray[$row][$c] = $data[$c];
                    }
                }
                print_r($resultarray);
                echo "解析文件" . $file . "结束";
                $weibopay->write_log("解析文件" . $file . "结束");
                fclose($handle);
            }
        }
        closedir($handler);
        $weibopay->write_log("****************sftp下载对账文件结束*************");

    }
?>