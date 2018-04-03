<?php
$result = json_decode(uploadFile($_FILES['file'], './storage'), true);
var_dump($result);
/**
 * ʹ��ϵͳȫ�ֱ�����$_FILES ʵ���ϴ��ļ���ָ����Ŀ¼
 * @param array $cert ϵͳȫ�ֱ�����$_FILES['myFile']����Ҫ������
 * @param string $savePath ָ���ļ������·����Ĭ�ϣ�'./uploads/������/'
 * @example
 * $cert = $_FILES['file'];
 * $savePath = './updates/';
 * @return json array
 * {
 *      "success": true,
 *       "message": "UPLOAD SUCCESS!",
 *       "file_info": {
 *           "file_path": "./updates/0_0_1228_6193#326_1.jpg",
 *           "file_type": "image/jpeg",
 *           "file_size": "0.37MB"
 *       }
 * }
 * @author fuliang
 * @date 2018-04-03
 */
function uploadFile ($cert, $savePath = '') {
    header('Content-Type: text/html; charset=utf-8');
    try {

        /*��֤����*/
        if (!is_array($cert)) {
            throw new Exception('PARAMS ERROR!');
        }

        /*��֤�Ƿ��ϴ��ɹ�*/
        if ($cert['error'] > 0) {
            throw new Exception('UPLOAD FAIL��' . $cert['error']);
        }

        $url      = $cert['tmp_name'];
        $name     = $cert['name'];                           //�ļ���
        $fileSize = round($cert['size']/1024/1024, 2) .'MB'; //�ļ���С
        $fileType = $cert['type'];                           // �ļ�����

        /*�����ϴ�·����Ĭ�ϱ��浽uploads�ļ����µĵ�ǰ����Ŀ¼��*/
        if (empty($savePath)) {
            $savePath = './uploads/' . date('Ymd');
        }
        $savePath .= '/';
        if (!is_dir($savePath)) {
            if (!mkdir($savePath, 0755, true)) {
                throw new \Exception('CREATE DIR FAIL!');
            }
        }
        /*��ԭ�ļ����������ϴ����ļ�*/
        $filePath = $savePath . $name;
        if (is_file($filePath)) {
            throw new \Exception('FILE EXIST!');
        }

        /*�����ļ���ָ��Ŀ¼*/
        if (!move_uploaded_file($url, iconv("UTF-8", "GBK", $filePath))) {
            throw new Exception('FAILED TO UPLOAD FILES TO THE SPECIFIED DIR!');
        }
        $response = [
            'success' => true ,
            'message' => 'UPLOAD SUCCESS!',
            'file_info' => [
                'file_path' => $filePath,
                'file_type' => $fileType,
                'file_size' => $fileSize,
            ]
        ];
    } catch (\Exception $e) {
        $response = ['success' => false , 'message' => $e->getMessage()];
    }
    ob_clean();

    return json_encode($response);
}